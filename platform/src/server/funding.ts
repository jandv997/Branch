import type { FundingSource, LicenseTier, Prisma } from "@prisma/client";
import {
  applyRankUpSpillover,
  classifyFunding,
  compressPayUplines,
  createsDirectDepositVolume,
  evaluateFastStart,
  fastStartConsiders,
  licenseByTier,
  treePaysOn,
  volumeCreditsForDeposit,
  whyTreeNotPaid,
  type CompConfig,
} from "@/domain/comp";
import { Money } from "@/domain/money";
import { writeAudit } from "./audit";
import { getCompConfig } from "./comp-config";
import { postLedger } from "./ledger";
import { walkUpline, monthKey } from "./tree";
import { prisma } from "./db";

function walletKindForSource(source: FundingSource) {
  switch (source) {
    case "REFERRAL_WALLET":
      return "REFERRAL" as const;
    case "EARNINGS_WALLET":
      return "EARNINGS" as const;
    case "STAKING_WALLET":
      return "STAKING" as const;
    default:
      return null;
  }
}

export async function fundPortfolio(opts: {
  userId: string;
  amountCents: bigint;
  source: FundingSource;
  portfolioId?: string;
  actorId?: string;
  ip?: string | null;
  reason?: string;
}) {
  if (opts.amountCents <= 0n) throw new Error("Amount must be positive");
  const cfg = await getCompConfig();

  return prisma.$transaction(async (tx) => {
    const user = await tx.user.findUniqueOrThrow({ where: { id: opts.userId } });
    if (user.status !== "ACTIVE") throw new Error("USER_NOT_ACTIVE");
    if (user.licenseStatus !== "ACTIVE" || !user.licenseId) throw new Error("LICENSE_REQUIRED");
    const license = await tx.license.findUniqueOrThrow({ where: { id: user.licenseId } });
    const def = licenseByTier(cfg, license.tier);

    let portfolio;
    let isNew = false;
    if (opts.portfolioId) {
      portfolio = await tx.portfolio.findUniqueOrThrow({ where: { id: opts.portfolioId } });
      if (portfolio.userId !== opts.userId) throw new Error("PORTFOLIO_NOT_OWNED");
      if (portfolio.status === "CANCELLED" || portfolio.status === "COMPLETED") throw new Error("PORTFOLIO_CLOSED");
    } else {
      if (opts.amountCents < BigInt(def.minFundCents)) throw new Error("BELOW_MIN_FUND");
      isNew = true;
      const now = new Date();
      const end = new Date(now);
      end.setUTCDate(end.getUTCDate() + def.cycleDays);
      portfolio = await tx.portfolio.create({
        data: {
          userId: opts.userId,
          licenseTier: license.tier,
          principalCents: 0n,
          capCents: BigInt(def.capCents),
          cycleStart: now,
          cycleEnd: end,
          dailyCapBps: def.dailyCapBps,
        },
      });
    }

    const nextPrincipal = portfolio.principalCents + opts.amountCents;
    if (nextPrincipal > portfolio.capCents) throw new Error("EXCEEDS_LICENSE_CAP");

    const sourceWallet = walletKindForSource(opts.source);
    if (sourceWallet) {
      await postLedger(tx, {
        userId: opts.userId,
        wallet: sourceWallet,
        direction: "DEBIT",
        amountCents: opts.amountCents,
        type: "PORTFOLIO_FUND",
        refType: "Portfolio",
        refId: portfolio.id,
        actorId: opts.actorId ?? opts.userId,
        reason: opts.reason ?? `Fund from ${opts.source}`,
        ip: opts.ip,
      });
    }

    const funding = await tx.portfolioFunding.create({
      data: {
        portfolioId: portfolio.id,
        source: opts.source,
        amountCents: opts.amountCents,
        isNewPortfolio: isNew,
      },
    });

    await tx.portfolio.update({
      where: { id: portfolio.id },
      data: { principalCents: nextPrincipal },
    });

    if (!user.activatedAt) {
      await tx.user.update({ where: { id: user.id }, data: { activatedAt: new Date() } });
    }

    await applyCompensation(tx, cfg, {
      depositorId: opts.userId,
      amountCents: opts.amountCents,
      source: opts.source,
      isNewPortfolio: isNew,
      fundingId: funding.id,
      actorId: opts.actorId ?? opts.userId,
    });

    await writeAudit(tx, {
      actorId: opts.actorId ?? opts.userId,
      subjectId: opts.userId,
      action: isNew ? "portfolio.open" : "portfolio.topup",
      entity: "Portfolio",
      entityId: portfolio.id,
      after: { source: opts.source, amountCents: opts.amountCents.toString(), isNew },
      ip: opts.ip,
    });

    return { portfolioId: portfolio.id, fundingId: funding.id, isNew, principalCents: nextPrincipal };
  });
}

async function applyCompensation(
  tx: Prisma.TransactionClient,
  cfg: CompConfig,
  opts: {
    depositorId: string;
    amountCents: bigint;
    source: FundingSource;
    isNewPortfolio: boolean;
    fundingId: string;
    actorId: string;
  },
) {
  const kind = classifyFunding(opts.source, opts.isNewPortfolio);
  const upline = await walkUpline(opts.depositorId);
  const nodes = upline.map(({ raw: _r, ...n }) => n);

  if (createsDirectDepositVolume(kind)) {
    const credits = volumeCreditsForDeposit({
      kind,
      amountCents: opts.amountCents,
      depositorId: opts.depositorId,
      upline: nodes,
    });
    const mk = monthKey();
    for (const c of credits) {
      const u = await tx.user.findUniqueOrThrow({ where: { id: c.userId } });
      const keep =
        u.keepTvMonthKey === mk ? u.keepTvMonthCents + c.tvCents : c.tvCents;
      const meters = applyRankUpSpillover(cfg, {
        rank: u.rank,
        psvMeterCents: u.psvMeterCents + c.psvCents,
        tvMeterCents: u.tvMeterCents + c.tvCents,
        paidOneTime: (
          await tx.rankBonusPaid.findMany({ where: { userId: u.id } })
        ).map((p) => p.rank),
      });
      await tx.user.update({
        where: { id: u.id },
        data: {
          psvMeterCents: meters.psvMeterCents,
          tvMeterCents: meters.tvMeterCents,
          keepTvMonthCents: keep,
          keepTvMonthKey: mk,
          rank: meters.rank,
          salaryActive: meters.rank !== "NONE" ? u.salaryActive || meters.promotions.length > 0 : u.salaryActive,
        },
      });
      for (const p of meters.promotions) {
        await tx.rankEvent.create({
          data: {
            userId: u.id,
            fromRank: p.from,
            toRank: p.to,
            kind: "RANK_UP",
            bonusCents: p.bonusCents,
            bonusPaid: p.payBonus,
            meta: { fundingId: opts.fundingId },
          },
        });
        if (p.payBonus) {
          await tx.rankBonusPaid.create({
            data: { userId: u.id, rank: p.to, amountCents: p.bonusCents },
          });
          await postLedger(tx, {
            userId: u.id,
            wallet: cfg.rankPayoutWallet,
            direction: "CREDIT",
            amountCents: p.bonusCents,
            type: "RANK_BONUS",
            refType: "RankEvent",
            refId: p.to,
            actorId: opts.actorId,
            reason: `One-time ${p.to}`,
          });
        }
      }
    }
  }

  let treePaid = 0n;
  const why = whyTreeNotPaid(kind);
  if (treePaysOn(kind)) {
    const slots = compressPayUplines(nodes, cfg, Money.fromCents(opts.amountCents));
    for (const slot of slots) {
      treePaid += slot.amountCents;
      if (slot.amountCents <= 0n) continue;
      await tx.referralPayout.create({
        data: {
          earnerId: slot.userId,
          sourceUserId: opts.depositorId,
          level: slot.payLevel,
          compressed: slot.compressed,
          amountCents: slot.amountCents,
          bps: slot.bps,
          baseCents: opts.amountCents,
          reasonCode: "PAID",
          refType: "PortfolioFunding",
          refId: opts.fundingId,
        },
      });
      await postLedger(tx, {
        userId: slot.userId,
        wallet: "REFERRAL",
        direction: "CREDIT",
        amountCents: slot.amountCents,
        type: "TREE_COMMISSION",
        refType: "PortfolioFunding",
        refId: opts.fundingId,
        actorId: opts.actorId,
        meta: { payLevel: slot.payLevel, compressed: slot.compressed },
      });
    }
  } else {
    await tx.referralPayout.create({
      data: {
        earnerId: opts.depositorId,
        sourceUserId: opts.depositorId,
        level: 0,
        amountCents: 0n,
        bps: 0,
        baseCents: opts.amountCents,
        reasonCode: why ?? "NOT_TREE_EVENT",
        refType: "PortfolioFunding",
        refId: opts.fundingId,
      },
    });
  }

  if (fastStartConsiders(kind)) {
    const l1 = upline.find((u) => u.genealogicalLevel === 1);
    if (l1) {
      const already = await tx.fastStartPayout.findUnique({
        where: { depositRef: opts.fundingId },
      });
      const window = await fastStartWindowStats(tx, l1.userId, l1.raw.activatedAt);
      const fs = evaluateFastStart(cfg, {
        now: new Date(),
        earnerId: l1.userId,
        earnerActivatedAt: l1.raw.activatedAt,
        depositorId: opts.depositorId,
        genealogicalLevelFromEarner: 1,
        amountCents: opts.amountCents,
        kind,
        alreadyPaidOnDeposit: Boolean(already),
        isSelf: l1.userId === opts.depositorId,
        isWash: false,
        window,
        treePaidOnThisDepositCents: treePaid,
      });
      if (fs.eligible && fs.amountCents > 0n) {
        await tx.fastStartPayout.create({
          data: {
            earnerId: l1.userId,
            sourceUserId: opts.depositorId,
            depositRef: opts.fundingId,
            rawBps: fs.rawBps,
            clippedBps: fs.clippedBps,
            amountCents: fs.amountCents,
            volumePath: fs.volumePath,
            headcountPath: fs.headcountPath,
            clipReason: fs.clipReason,
          },
        });
        await postLedger(tx, {
          userId: l1.userId,
          wallet: "REFERRAL",
          direction: "CREDIT",
          amountCents: fs.amountCents,
          type: "FAST_START",
          refType: "PortfolioFunding",
          refId: opts.fundingId,
          actorId: opts.actorId,
          meta: { volumePath: fs.volumePath, headcountPath: fs.headcountPath, clip: fs.clipReason },
        });
      }
    }
  }
}

async function fastStartWindowStats(tx: Prisma.TransactionClient, earnerId: string, activatedAt: Date | null) {
  if (!activatedAt) {
    return { l1DdCents: 0n, l1l3DdCents: 0n, fundedL1Count: 0, fundedL1L3Count: 0 };
  }
  const end = new Date(activatedAt);
  end.setUTCDate(end.getUTCDate() + 14);
  const l1 = await tx.user.findMany({ where: { sponsorId: earnerId }, select: { id: true } });
  const l1Ids = l1.map((u) => u.id);
  const l2 = l1Ids.length
    ? await tx.user.findMany({ where: { sponsorId: { in: l1Ids } }, select: { id: true } })
    : [];
  const l2Ids = l2.map((u) => u.id);
  const l3 = l2Ids.length
    ? await tx.user.findMany({ where: { sponsorId: { in: l2Ids } }, select: { id: true } })
    : [];
  const l3Ids = l3.map((u) => u.id);
  const l1l3 = [...l1Ids, ...l2Ids, ...l3Ids];

  const fundingsL1 = l1Ids.length
    ? await tx.portfolioFunding.findMany({
        where: {
          source: "DIRECT_DEPOSIT",
          createdAt: { gte: activatedAt, lt: end },
          portfolio: { userId: { in: l1Ids } },
        },
      })
    : [];
  const fundingsL1L3 = l1l3.length
    ? await tx.portfolioFunding.findMany({
        where: {
          source: "DIRECT_DEPOSIT",
          createdAt: { gte: activatedAt, lt: end },
          portfolio: { userId: { in: l1l3 } },
        },
      })
    : [];

  const l1Portfolios = fundingsL1.length
    ? await tx.portfolio.findMany({
        where: { id: { in: [...new Set(fundingsL1.map((f) => f.portfolioId))] } },
        select: { userId: true },
      })
    : [];
  const l1l3Portfolios = fundingsL1L3.length
    ? await tx.portfolio.findMany({
        where: { id: { in: [...new Set(fundingsL1L3.map((f) => f.portfolioId))] } },
        select: { userId: true },
      })
    : [];

  return {
    l1DdCents: fundingsL1.reduce((a, f) => a + f.amountCents, 0n),
    l1l3DdCents: fundingsL1L3.reduce((a, f) => a + f.amountCents, 0n),
    fundedL1Count: new Set(l1Portfolios.map((p) => p.userId)).size,
    fundedL1L3Count: new Set(l1l3Portfolios.map((p) => p.userId)).size,
  };
}

export async function purchaseLicense(opts: {
  userId: string;
  tier: LicenseTier;
  actorId?: string;
}) {
  const cfg = await getCompConfig();
  const def = licenseByTier(cfg, opts.tier);
  return prisma.$transaction(async (tx) => {
    const user = await tx.user.findUniqueOrThrow({ where: { id: opts.userId } });
    if (user.status !== "ACTIVE") throw new Error("USER_NOT_ACTIVE");
    const license = await tx.license.findUniqueOrThrow({ where: { tier: opts.tier } });
    const first = !user.licenseFirstPaid;
    const renews = new Date();
    renews.setUTCDate(renews.getUTCDate() + 365);
    await tx.user.update({
      where: { id: user.id },
      data: {
        licenseId: license.id,
        licenseStatus: "ACTIVE",
        licenseRenewsAt: renews,
        licenseFirstPaid: true,
        activatedAt: user.activatedAt ?? new Date(),
      },
    });
    if (first) {
      const upline = await walkUpline(opts.userId);
      const slots = compressPayUplines(
        upline.map(({ raw: _r, ...n }) => n),
        cfg,
        Money.fromCents(def.priceCents),
      );
      for (const slot of slots) {
        if (slot.amountCents <= 0n) continue;
        await tx.referralPayout.create({
          data: {
            earnerId: slot.userId,
            sourceUserId: opts.userId,
            level: slot.payLevel,
            compressed: slot.compressed,
            amountCents: slot.amountCents,
            bps: slot.bps,
            baseCents: BigInt(def.priceCents),
            reasonCode: "PAID",
            refType: "License",
            refId: license.id,
          },
        });
        await postLedger(tx, {
          userId: slot.userId,
          wallet: "REFERRAL",
          direction: "CREDIT",
          amountCents: slot.amountCents,
          type: "TREE_COMMISSION",
          refType: "License",
          refId: license.id,
          actorId: opts.actorId ?? opts.userId,
        });
      }
    }
    await writeAudit(tx, {
      actorId: opts.actorId ?? opts.userId,
      subjectId: opts.userId,
      action: first ? "license.purchase" : "license.renew",
      entity: "User",
      entityId: user.id,
      after: { tier: opts.tier, first },
    });
    return { first, renewsAt: renews, priceCents: def.priceCents };
  });
}

export async function moveWallet(opts: {
  userId: string;
  from: "EARNINGS" | "AVAILABLE" | "REFERRAL" | "STAKING";
  to: "EARNINGS" | "AVAILABLE" | "REFERRAL" | "STAKING";
  amountCents: bigint;
  type: string;
  actorId?: string;
}) {
  if (opts.from === opts.to) throw new Error("SAME_WALLET");
  if (opts.amountCents <= 0n) throw new Error("Amount must be positive");
  const cfg = await getCompConfig();
  if (opts.from === "EARNINGS" && opts.to === "AVAILABLE" && !cfg.earningsToAvailableEnabled) {
    throw new Error("EARNINGS_TRANSFER_DISABLED");
  }
  return prisma.$transaction(async (tx) => {
    await postLedger(tx, {
      userId: opts.userId,
      wallet: opts.from,
      direction: "DEBIT",
      amountCents: opts.amountCents,
      type: opts.type,
      actorId: opts.actorId ?? opts.userId,
    });
    await postLedger(tx, {
      userId: opts.userId,
      wallet: opts.to,
      direction: "CREDIT",
      amountCents: opts.amountCents,
      type: opts.type,
      actorId: opts.actorId ?? opts.userId,
    });
    return { ok: true as const };
  });
}
