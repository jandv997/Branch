import { TRPCError } from "@trpc/server";
import { z } from "zod";
import { FundingSourceSchema, LicenseTierSchema } from "@/domain/comp/config";
import { evaluateFastStart } from "@/domain/comp/fast-start";
import { nextRank, rankByCode } from "@/domain/comp/config";
import { prisma } from "../../db";
import { getCompConfig } from "../../comp-config";
import { fundPortfolio, moveWallet, purchaseLicense } from "../../funding";
import { withIdempotency } from "../../idempotency";
import { getPaymentAdapter } from "../../payments";
import { depositMemo } from "../../crypto";
import { walkUpline } from "../../tree";
import { verifyUserTotp } from "../../auth";
import { postLedger } from "../../ledger";
import { protectedProcedure, requireTotp, router } from "../init";
import { ledgerKind, mergeActivity } from "@/lib/activity";

const cents = z.string().regex(/^\d+$/).transform((s) => BigInt(s));

export const userRouter = router({
  overview: protectedProcedure.query(async ({ ctx }) => {
    const cfg = await getCompConfig();
    const user = await prisma.user.findUniqueOrThrow({
      where: { id: ctx.user.id },
      include: { license: true, wallets: true, portfolios: true },
    });
    const today = new Date().toISOString().slice(0, 10);
    const credits = await prisma.dailyCredit.findMany({
      where: { userId: user.id, businessDate: today },
    });
    const creditToday = credits.reduce((a, c) => a + c.amountCents, 0n);
    const capToday = user.portfolios
      .filter((p) => p.status === "ACTIVE" && !p.paused)
      .reduce((a, p) => a + (p.principalCents * BigInt(p.dailyCapBps)) / 10_000n, 0n);
    const nxt = nextRank(cfg, user.rank);
    const fsDays = user.activatedAt
      ? Math.max(0, 14 - Math.floor((Date.now() - user.activatedAt.getTime()) / 86400000))
      : null;
    return {
      user,
      wallets: user.wallets,
      creditToday,
      capToday,
      nextRank: nxt,
      psvMeterCents: user.psvMeterCents,
      tvMeterCents: user.tvMeterCents,
      keepTvMonthCents: user.keepTvMonthCents,
      keepTvMonthKey: user.keepTvMonthKey,
      fsDaysLeft: fsDays !== null && fsDays <= 14 ? fsDays : null,
      referralUrl: `${process.env.APP_URL ?? "http://localhost:3000"}/register?ref=${user.id}`,
      patentPending: cfg.patentPending as true,
      copy: cfg.copy,
    };
  }),

  licensePurchase: protectedProcedure
    .input(z.object({ tier: LicenseTierSchema }))
    .mutation(async ({ ctx, input }) => {
      const key = ctx.idempotencyKey ?? `lic-${ctx.user.id}-${input.tier}-${Date.now()}`;
      return withIdempotency({
        key,
        userId: ctx.user.id,
        route: "licensePurchase",
        fn: async () => {
          const cfg = await getCompConfig();
          const def = cfg.licenses.find((l) => l.tier === input.tier)!;
          await getPaymentAdapter().payLicense(ctx.user.id, input.tier, BigInt(def.priceCents));
          return purchaseLicense({ userId: ctx.user.id, tier: input.tier });
        },
      });
    }),

  portfolios: protectedProcedure.query(async ({ ctx }) => {
    return prisma.portfolio.findMany({
      where: { userId: ctx.user.id },
      include: { fundings: { orderBy: { createdAt: "desc" } } },
      orderBy: { createdAt: "desc" },
    });
  }),

  fundPortfolio: protectedProcedure
    .input(
      z.object({
        amountCents: cents,
        source: FundingSourceSchema,
        portfolioId: z.string().optional(),
      }),
    )
    .mutation(async ({ ctx, input }) => {
      if (ctx.user.status !== "ACTIVE") throw new TRPCError({ code: "FORBIDDEN" });
      const key = ctx.idempotencyKey ?? `fund-${ctx.user.id}-${input.portfolioId ?? "new"}-${input.amountCents}-${Date.now()}`;
      return withIdempotency({
        key,
        userId: ctx.user.id,
        route: "fundPortfolio",
        fn: () =>
          fundPortfolio({
            userId: ctx.user.id,
            amountCents: input.amountCents,
            source: input.source,
            portfolioId: input.portfolioId,
            ip: ctx.ip,
          }),
      });
    }),

  pausePortfolio: protectedProcedure
    .input(z.object({ id: z.string(), paused: z.boolean() }))
    .mutation(async ({ ctx, input }) => {
      await prisma.portfolio.updateMany({
        where: { id: input.id, userId: ctx.user.id },
        data: { paused: input.paused },
      });
      return { ok: true };
    }),

  wallets: protectedProcedure.query(async ({ ctx }) => {
    return prisma.wallet.findMany({ where: { userId: ctx.user.id } });
  }),

  ledger: protectedProcedure
    .input(
      z.object({
        wallet: z.enum(["AVAILABLE", "EARNINGS", "REFERRAL", "STAKING", "PENDING"]).optional(),
        cursor: z.string().optional(),
        take: z.number().min(1).max(200).default(50),
      }),
    )
    .query(async ({ ctx, input }) => {
      return prisma.ledgerEntry.findMany({
        where: { userId: ctx.user.id, wallet: input.wallet },
        orderBy: { createdAt: "desc" },
        take: input.take,
        skip: input.cursor ? 1 : 0,
        cursor: input.cursor ? { id: input.cursor } : undefined,
      });
    }),

  moveEarnings: protectedProcedure
    .input(z.object({ amountCents: cents }))
    .mutation(async ({ ctx, input }) => {
      return moveWallet({
        userId: ctx.user.id,
        from: "EARNINGS",
        to: "AVAILABLE",
        amountCents: input.amountCents,
        type: "EARNINGS_TO_AVAILABLE",
      });
    }),

  stake: protectedProcedure
    .input(z.object({ amountCents: cents }))
    .mutation(async ({ ctx, input }) => {
      const cfg = await getCompConfig();
      const lockedUntil = new Date();
      lockedUntil.setUTCDate(lockedUntil.getUTCDate() + cfg.stakingLockDays);
      await prisma.$transaction(async (tx) => {
        await postLedger(tx, {
          userId: ctx.user.id,
          wallet: "AVAILABLE",
          direction: "DEBIT",
          amountCents: input.amountCents,
          type: "STAKING_LOCK",
        });
        await postLedger(tx, {
          userId: ctx.user.id,
          wallet: "STAKING",
          direction: "CREDIT",
          amountCents: input.amountCents,
          type: "STAKING_LOCK",
        });
        await tx.stakingLot.create({
          data: { userId: ctx.user.id, amountCents: input.amountCents, lockedUntil },
        });
      });
      return { lockedUntil, note: "Staking does not create PSV, TV, tree commission, or Fast Start." };
    }),

  unstake: protectedProcedure
    .input(z.object({ lotId: z.string() }))
    .mutation(async ({ ctx, input }) => {
      const lot = await prisma.stakingLot.findFirst({
        where: { id: input.lotId, userId: ctx.user.id, releasedAt: null },
      });
      if (!lot) throw new TRPCError({ code: "NOT_FOUND" });
      if (lot.lockedUntil > new Date()) throw new TRPCError({ code: "FORBIDDEN", message: "Still locked" });
      await prisma.$transaction(async (tx) => {
        await postLedger(tx, {
          userId: ctx.user.id,
          wallet: "STAKING",
          direction: "DEBIT",
          amountCents: lot.amountCents,
          type: "STAKING_UNLOCK",
          refId: lot.id,
        });
        await postLedger(tx, {
          userId: ctx.user.id,
          wallet: "AVAILABLE",
          direction: "CREDIT",
          amountCents: lot.amountCents,
          type: "STAKING_UNLOCK",
          refId: lot.id,
        });
        await tx.stakingLot.update({ where: { id: lot.id }, data: { releasedAt: new Date() } });
      });
      return { ok: true };
    }),

  stakingLots: protectedProcedure.query(async ({ ctx }) => {
    return prisma.stakingLot.findMany({ where: { userId: ctx.user.id }, orderBy: { createdAt: "desc" } });
  }),

  createDeposit: protectedProcedure
    .input(
      z.object({
        amountCents: cents,
        network: z.string().default("TRC20"),
        portfolioId: z.string().optional(),
      }),
    )
    .mutation(async ({ ctx, input }) => {
      const memo = depositMemo();
      const dep = await prisma.deposit.create({
        data: {
          userId: ctx.user.id,
          amountCents: input.amountCents,
          network: input.network,
          memo,
          portfolioId: input.portfolioId,
          address: "TDEVQORVEXDEMOADDRESS1111111111111",
        },
      });
      const adapter = getPaymentAdapter();
      if (adapter.name === "dev") {
        const sim = await adapter.simulateDirectDeposit({
          userId: ctx.user.id,
          amountCents: input.amountCents,
          memo,
        });
        await prisma.deposit.update({
          where: { id: dep.id },
          data: { status: "CONFIRMED", txHash: sim.txHash, confirmedAt: new Date() },
        });
        const funded = await fundPortfolio({
          userId: ctx.user.id,
          amountCents: input.amountCents,
          source: "DIRECT_DEPOSIT",
          portfolioId: input.portfolioId,
          ip: ctx.ip,
        });
        return { deposit: dep, funded, simulated: true };
      }
      return { deposit: dep, simulated: false };
    }),

  deposits: protectedProcedure.query(async ({ ctx }) => {
    return prisma.deposit.findMany({ where: { userId: ctx.user.id }, orderBy: { createdAt: "desc" } });
  }),

  requestWithdraw: protectedProcedure
    .input(
      z.object({
        amountCents: cents,
        network: z.string(),
        address: z.string().min(8),
        totp: z.string(),
      }),
    )
    .mutation(async ({ ctx, input }) => {
      requireTotp(ctx.user);
      const cfg = await getCompConfig();
      if (cfg.haltWithdraws) throw new TRPCError({ code: "FORBIDDEN", message: "Withdrawals halted" });
      const halt = await prisma.systemHalt.findUnique({ where: { id: "singleton" } });
      if (halt?.haltWithdraws) throw new TRPCError({ code: "FORBIDDEN", message: "Withdrawals halted" });
      const user = await prisma.user.findUniqueOrThrow({ where: { id: ctx.user.id } });
      const ok = await verifyUserTotp(user, input.totp);
      if (!ok) throw new TRPCError({ code: "UNAUTHORIZED", message: "Invalid 2FA" });
      if (input.amountCents >= BigInt(cfg.kycWithdrawThresholdCents) && user.kycStatus !== "APPROVED") {
        throw new TRPCError({ code: "FORBIDDEN", message: "KYC required above threshold" });
      }
      const wl = await prisma.whitelistAddress.findFirst({
        where: { userId: ctx.user.id, network: input.network, address: input.address },
      });
      if (!wl) throw new TRPCError({ code: "BAD_REQUEST", message: "Address not whitelisted" });
      if (wl.unlockedAt > new Date()) throw new TRPCError({ code: "FORBIDDEN", message: "Address in 24h lock" });

      const key = ctx.idempotencyKey ?? `wd-${ctx.user.id}-${input.amountCents}-${Date.now()}`;
      return withIdempotency({
        key,
        userId: ctx.user.id,
        route: "withdraw",
        fn: async () => {
          const expiresAt = new Date(Date.now() + cfg.withdrawExpiryHours * 3600 * 1000);
          return prisma.$transaction(async (tx) => {
            await postLedger(tx, {
              userId: ctx.user.id,
              wallet: "AVAILABLE",
              direction: "DEBIT",
              amountCents: input.amountCents,
              type: "WITHDRAW_HOLD",
            });
            await postLedger(tx, {
              userId: ctx.user.id,
              wallet: "PENDING",
              direction: "CREDIT",
              amountCents: input.amountCents,
              type: "WITHDRAW_HOLD",
            });
            const w = await tx.withdrawal.create({
              data: {
                userId: ctx.user.id,
                amountCents: input.amountCents,
                network: input.network,
                address: input.address,
                totpVerified: true,
                expiresAt,
              },
            });
            return { id: w.id, status: w.status, expiresAt };
          });
        },
      });
    }),

  cancelWithdraw: protectedProcedure
    .input(z.object({ id: z.string() }))
    .mutation(async ({ ctx, input }) => {
      const w = await prisma.withdrawal.findFirst({
        where: { id: input.id, userId: ctx.user.id, status: "PENDING" },
      });
      if (!w) throw new TRPCError({ code: "NOT_FOUND" });
      await prisma.$transaction(async (tx) => {
        await tx.withdrawal.update({ where: { id: w.id }, data: { status: "CANCELLED" } });
        await postLedger(tx, {
          userId: ctx.user.id,
          wallet: "PENDING",
          direction: "DEBIT",
          amountCents: w.amountCents,
          type: "WITHDRAW_CANCEL",
          refId: w.id,
        });
        await postLedger(tx, {
          userId: ctx.user.id,
          wallet: "AVAILABLE",
          direction: "CREDIT",
          amountCents: w.amountCents,
          type: "WITHDRAW_CANCEL",
          refId: w.id,
        });
      });
      return { ok: true };
    }),

  withdrawals: protectedProcedure.query(async ({ ctx }) => {
    return prisma.withdrawal.findMany({ where: { userId: ctx.user.id }, orderBy: { createdAt: "desc" } });
  }),

  team: protectedProcedure.query(async ({ ctx }) => {
    const l1 = await prisma.user.findMany({
      where: { sponsorId: ctx.user.id },
      select: { id: true, email: true, rank: true, licenseStatus: true, createdAt: true },
    });
    async function collect(ids: string[], depth: number): Promise<{ level: number; users: typeof l1 }[]> {
      if (depth > 7 || ids.length === 0) return [];
      const users = await prisma.user.findMany({
        where: { sponsorId: { in: ids } },
        select: { id: true, email: true, rank: true, licenseStatus: true, createdAt: true },
      });
      const rest = await collect(users.map((u) => u.id), depth + 1);
      return [{ level: depth, users }, ...rest];
    }
    const levels = [{ level: 1, users: l1 }, ...(await collect(l1.map((u) => u.id), 2))];
    const me = await prisma.user.findUniqueOrThrow({ where: { id: ctx.user.id } });
    const fundedL1 = await prisma.portfolioFunding.groupBy({
      by: ["portfolioId"],
      where: { source: "DIRECT_DEPOSIT", portfolio: { userId: { in: l1.map((u) => u.id) } } },
    });
    return {
      levels,
      psvMeterCents: me.psvMeterCents,
      tvMeterCents: me.tvMeterCents,
      fundedL1Count: fundedL1.length,
    };
  }),

  referrals: protectedProcedure.query(async ({ ctx }) => {
    return prisma.referralPayout.findMany({
      where: { OR: [{ earnerId: ctx.user.id }, { sourceUserId: ctx.user.id }] },
      orderBy: { createdAt: "desc" },
      take: 200,
    });
  }),

  fastStart: protectedProcedure.query(async ({ ctx }) => {
    const cfg = await getCompConfig();
    const me = await prisma.user.findUniqueOrThrow({ where: { id: ctx.user.id } });
    const pays = await prisma.fastStartPayout.findMany({
      where: { earnerId: ctx.user.id },
      orderBy: { createdAt: "desc" },
    });
    const preview = evaluateFastStart(cfg, {
      now: new Date(),
      earnerId: me.id,
      earnerActivatedAt: me.activatedAt,
      depositorId: "preview",
      genealogicalLevelFromEarner: 1,
      amountCents: 100_000n,
      kind: "NEW_PORTFOLIO_DIRECT_DEPOSIT",
      alreadyPaidOnDeposit: false,
      isSelf: false,
      isWash: false,
      window: { l1DdCents: me.psvMeterCents, l1l3DdCents: me.tvMeterCents, fundedL1Count: 0, fundedL1L3Count: 0 },
      treePaidOnThisDepositCents: 0n,
    });
    const day = me.activatedAt
      ? Math.min(14, Math.max(1, Math.floor((Date.now() - me.activatedAt.getTime()) / 86400000) + 1))
      : 0;
    return { day, windowDays: cfg.fastStart.windowDays, pays, preview, copy: cfg.copy.fastStartOneEarner };
  }),

  ranks: protectedProcedure.query(async ({ ctx }) => {
    const cfg = await getCompConfig();
    const me = await prisma.user.findUniqueOrThrow({ where: { id: ctx.user.id } });
    const events = await prisma.rankEvent.findMany({ where: { userId: me.id }, orderBy: { createdAt: "desc" } });
    const paid = await prisma.rankBonusPaid.findMany({ where: { userId: me.id } });
    return {
      current: rankByCode(cfg, me.rank),
      next: nextRank(cfg, me.rank),
      psvMeterCents: me.psvMeterCents,
      tvMeterCents: me.tvMeterCents,
      keepTvMonthCents: me.keepTvMonthCents,
      salaryActive: me.salaryActive,
      rankHeldUntil: me.rankHeldUntil,
      events,
      paid,
      table: cfg.ranks,
    };
  }),

  analytics: protectedProcedure.query(async ({ ctx }) => {
    const credits = await prisma.dailyCredit.findMany({
      where: { userId: ctx.user.id },
      orderBy: { businessDate: "asc" },
      take: 90,
    });
    const fundings = await prisma.portfolioFunding.findMany({
      where: { portfolio: { userId: ctx.user.id } },
    });
    const dd = fundings.filter((f) => f.source === "DIRECT_DEPOSIT").reduce((a, f) => a + f.amountCents, 0n);
    const wallet = fundings.filter((f) => f.source !== "DIRECT_DEPOSIT").reduce((a, f) => a + f.amountCents, 0n);
    return { credits, dd, wallet };
  }),

  whitelist: protectedProcedure.query(async ({ ctx }) => {
    return prisma.whitelistAddress.findMany({ where: { userId: ctx.user.id } });
  }),

  addWhitelist: protectedProcedure
    .input(z.object({ network: z.string(), address: z.string().min(8), label: z.string().optional() }))
    .mutation(async ({ ctx, input }) => {
      const cfg = await getCompConfig();
      const unlockedAt = new Date(Date.now() + cfg.whitelistDelayHours * 3600 * 1000);
      return prisma.whitelistAddress.create({
        data: { userId: ctx.user.id, ...input, unlockedAt },
      });
    }),

  ipLog: protectedProcedure.query(async ({ ctx }) => {
    return prisma.ipLog.findMany({
      where: { userId: ctx.user.id },
      orderBy: { createdAt: "desc" },
      take: 50,
    });
  }),

  kycDocs: protectedProcedure.query(async ({ ctx }) => {
    const me = await prisma.user.findUniqueOrThrow({ where: { id: ctx.user.id } });
    const docs = await prisma.kycDoc.findMany({ where: { userId: ctx.user.id } });
    return { status: me.kycStatus, docs };
  }),

  tickets: protectedProcedure.query(async ({ ctx }) => {
    return prisma.ticket.findMany({
      where: { userId: ctx.user.id },
      include: { messages: true },
      orderBy: { createdAt: "desc" },
    });
  }),

  openTicket: protectedProcedure
    .input(z.object({ subject: z.string().min(3), body: z.string().min(3) }))
    .mutation(async ({ ctx, input }) => {
      return prisma.ticket.create({
        data: {
          userId: ctx.user.id,
          subject: input.subject,
          messages: { create: { authorId: ctx.user.id, body: input.body } },
        },
      });
    }),

  acceptLegal: protectedProcedure
    .input(z.object({ doc: z.string(), version: z.string() }))
    .mutation(async ({ ctx, input }) => {
      await prisma.legalAccept.upsert({
        where: { userId_doc_version: { userId: ctx.user.id, doc: input.doc, version: input.version } },
        create: { userId: ctx.user.id, ...input },
        update: {},
      });
      return { ok: true };
    }),

  legalAccepts: protectedProcedure.query(async ({ ctx }) => {
    return prisma.legalAccept.findMany({ where: { userId: ctx.user.id } });
  }),

  notifications: protectedProcedure.query(async ({ ctx }) => {
    return prisma.notification.findMany({
      where: { userId: ctx.user.id },
      orderBy: { createdAt: "desc" },
      take: 50,
    });
  }),

  markNotification: protectedProcedure
    .input(z.object({ id: z.string() }))
    .mutation(async ({ ctx, input }) => {
      await prisma.notification.updateMany({
        where: { id: input.id, userId: ctx.user.id },
        data: { readAt: new Date() },
      });
      return { ok: true };
    }),

  announcements: protectedProcedure.query(async () => {
    return prisma.announcement.findMany({ where: { active: true }, orderBy: { createdAt: "desc" } });
  }),

  upline: protectedProcedure.query(async ({ ctx }) => {
    return walkUpline(ctx.user.id, 7);
  }),

  activity: protectedProcedure
    .input(z.object({ take: z.number().min(1).max(200).default(60) }).optional())
    .query(async ({ ctx, input }) => {
      const take = input?.take ?? 60;
      const userId = ctx.user.id;
      const [ledger, ranks, announcements, notifications, deposits, withdrawals, fundings] = await Promise.all([
        prisma.ledgerEntry.findMany({ where: { userId }, orderBy: { createdAt: "desc" }, take }),
        prisma.rankEvent.findMany({ where: { userId }, orderBy: { createdAt: "desc" }, take: 40 }),
        prisma.announcement.findMany({ where: { active: true }, orderBy: { createdAt: "desc" }, take: 20 }),
        prisma.notification.findMany({ where: { userId }, orderBy: { createdAt: "desc" }, take: 20 }),
        prisma.deposit.findMany({ where: { userId }, orderBy: { createdAt: "desc" }, take: 20 }),
        prisma.withdrawal.findMany({ where: { userId }, orderBy: { createdAt: "desc" }, take: 20 }),
        prisma.portfolioFunding.findMany({
          where: { portfolio: { userId } },
          orderBy: { createdAt: "desc" },
          take: 30,
          include: { portfolio: { select: { licenseTier: true } } },
        }),
      ]);

      const items = mergeActivity(
        [
          ...ledger.map((r) => ({
            id: `led-${r.id}`,
            at: r.createdAt,
            kind: ledgerKind(r.type),
            title: `${r.direction} ${r.wallet} · ${r.type}`,
            detail: r.refType ? `${r.refType} ${r.refId ?? ""}`.trim() : r.type,
            amountCents: r.direction === "CREDIT" ? r.amountCents : -r.amountCents,
            href: "/app/wallets",
          })),
          ...ranks.map((r) => ({
            id: `rank-${r.id}`,
            at: r.createdAt,
            kind: "RANK" as const,
            title: `${r.kind}: ${r.fromRank} → ${r.toRank}`,
            detail: r.bonusPaid ? "One-time bonus posted to AVAILABLE" : "Rank meter / keep event",
            amountCents: r.bonusCents > 0n ? r.bonusCents : undefined,
            href: "/app/ranks",
          })),
          ...announcements.map((a) => ({
            id: `ann-${a.id}`,
            at: a.createdAt,
            kind: "SYSTEM" as const,
            title: a.title,
            detail: a.body,
            href: "/app/updates",
          })),
          ...notifications.map((n) => ({
            id: `ntf-${n.id}`,
            at: n.createdAt,
            kind: "SYSTEM" as const,
            title: n.title,
            detail: n.body,
          })),
          ...deposits.map((d) => ({
            id: `dep-${d.id}`,
            at: d.createdAt,
            kind: "DEPOSIT" as const,
            title: `Deposit ${d.status} · ${d.memo}`,
            detail: `${d.network} ${d.address ?? ""}`.trim(),
            amountCents: d.amountCents,
            href: "/app/deposit",
          })),
          ...withdrawals.map((w) => ({
            id: `wd-${w.id}`,
            at: w.createdAt,
            kind: "WITHDRAW" as const,
            title: `Withdraw ${w.status}`,
            detail: `${w.network} ${w.address}`,
            amountCents: w.amountCents,
            href: "/app/withdraw",
          })),
          ...fundings.map((f) => ({
            id: `fund-${f.id}`,
            at: f.createdAt,
            kind: "FUNDING" as const,
            title: `${f.isNewPortfolio ? "New portfolio" : "Top-up"} · ${f.source}`,
            detail: `${f.portfolio.licenseTier}${f.source === "DIRECT_DEPOSIT" ? " · creates volume as specified" : " · does not create PSV/TV/tree/FS"}`,
            amountCents: f.amountCents,
            href: "/app/portfolios",
          })),
        ],
        take,
      );
      return items;
    }),
});
