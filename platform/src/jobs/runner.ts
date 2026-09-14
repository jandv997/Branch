import { JobRunStatus } from "@prisma/client";
import { computeDailyCredit, EngineProvider, applyMonthlyKeep, weeklySalaryCents } from "@/domain/comp";
import { getCompConfig } from "../server/comp-config";
import { prisma } from "../server/db";
import { postLedger } from "../server/ledger";
import { monthKey } from "../server/tree";

export async function runJob(name: string, idempotencyKey: string, fn: (jobId: string) => Promise<Record<string, unknown>>) {
  const existing = await prisma.jobRun.findUnique({ where: { idempotencyKey } });
  if (existing && existing.status === "SUCCESS") {
    return { replayed: true, id: existing.id, stats: existing.stats };
  }
  const run = existing
    ? await prisma.jobRun.update({ where: { id: existing.id }, data: { status: "RUNNING", startedAt: new Date(), error: null } })
    : await prisma.jobRun.create({ data: { name, idempotencyKey, status: "RUNNING" } });
  try {
    const stats = await fn(run.id);
    await prisma.jobRun.update({
      where: { id: run.id },
      data: { status: "SUCCESS" as JobRunStatus, finishedAt: new Date(), stats: stats as object },
    });
    return { replayed: false, id: run.id, stats };
  } catch (err) {
    const message = err instanceof Error ? err.message : String(err);
    await prisma.jobRun.update({
      where: { id: run.id },
      data: { status: "FAILED", finishedAt: new Date(), error: message },
    });
    throw err;
  }
}

function utcDate(d = new Date()) {
  return d.toISOString().slice(0, 10);
}

export async function jobDailyCredits() {
  const day = utcDate();
  return runJob("dailyCredits", `dailyCredits:${day}`, async (jobId) => {
    const cfg = await getCompConfig();
    const halt = await prisma.systemHalt.findUnique({ where: { id: "singleton" } });
    const engine = new EngineProvider(cfg.engineDefaultBps);
    const portfolios = await prisma.portfolio.findMany({
      where: { status: "ACTIVE" },
      include: { user: true },
    });
    let credited = 0;
    let skipped = 0;
    for (const p of portfolios) {
      try {
        const result = computeDailyCredit(cfg, {
          principalCents: p.principalCents,
          licenseTier: p.licenseTier,
          licenseStatus: p.user.licenseStatus,
          paused: p.paused,
          haltCredits: Boolean(halt?.haltCredits || cfg.haltCredits),
          engineBps: engine.currentBps(),
          dailyCapOverrideBps: p.dailyCapBps,
        });
        if (result.amountCents <= 0n) {
          skipped += 1;
          continue;
        }
        await prisma.$transaction(async (tx) => {
          await tx.dailyCredit.create({
            data: {
              portfolioId: p.id,
              userId: p.userId,
              businessDate: day,
              engineBps: result.engineBps,
              licenseCapBps: result.licenseCapBps,
              appliedBps: result.appliedBps,
              principalCents: p.principalCents,
              amountCents: result.amountCents,
            },
          });
          await postLedger(tx, {
            userId: p.userId,
            wallet: "EARNINGS",
            direction: "CREDIT",
            amountCents: result.amountCents,
            type: "DAILY_CREDIT",
            refType: "Portfolio",
            refId: p.id,
            meta: { businessDate: day, appliedBps: result.appliedBps },
            reason: "Daily engine credit (up to license cap)",
          });
        });
        credited += 1;
      } catch (err) {
        const message = err instanceof Error ? err.message : String(err);
        if (message.includes("Unique constraint") || message.includes("dailyCredit")) {
          skipped += 1;
          continue;
        }
        await prisma.jobException.create({
          data: { jobRunId: jobId, refType: "Portfolio", refId: p.id, message },
        });
      }
    }
    return { day, credited, skipped, total: portfolios.length };
  });
}

export async function jobWeeklySalary() {
  const week = utcDate();
  return runJob("weeklySalary", `weeklySalary:${week}`, async (jobId) => {
    const cfg = await getCompConfig();
    const users = await prisma.user.findMany({
      where: { salaryActive: true, status: "ACTIVE", rank: { not: "NONE" } },
    });
    let paid = 0;
    for (const u of users) {
      const amount = weeklySalaryCents(cfg, u.rank, u.salaryActive);
      if (amount <= 0n) continue;
      try {
        await prisma.$transaction(async (tx) => {
          await postLedger(tx, {
            userId: u.id,
            wallet: cfg.rankPayoutWallet,
            direction: "CREDIT",
            amountCents: amount,
            type: "SALARY",
            refType: "User",
            refId: u.id,
            meta: { week, rank: u.rank },
            reason: "Weekly rank salary",
          });
        });
        paid += 1;
      } catch (err) {
        await prisma.jobException.create({
          data: {
            jobRunId: jobId,
            refType: "User",
            refId: u.id,
            message: err instanceof Error ? err.message : String(err),
          },
        });
      }
    }
    return { week, paid };
  });
}

export async function jobMonthlyRanks() {
  const key = monthKey();
  return runJob("monthlyRanks", `monthlyRanks:${key}`, async () => {
    const cfg = await getCompConfig();
    const users = await prisma.user.findMany({ where: { rank: { not: "NONE" } } });
    let kept = 0;
    let held = 0;
    let dropped = 0;
    const now = new Date();
    for (const u of users) {
      const keep = u.keepTvMonthKey === key ? u.keepTvMonthCents : 0n;
      const r = applyMonthlyKeep({
        cfg,
        rank: u.rank,
        keepTvCents: keep,
        missStreak: u.keepMissStreak,
        now,
      });
      await prisma.user.update({
        where: { id: u.id },
        data: {
          salaryActive: r.salaryActive,
          keepMissStreak: r.missStreak,
          rank: r.rank,
          rankHeldUntil: r.rankHeldUntil,
        },
      });
      await prisma.rankEvent.create({
        data: {
          userId: u.id,
          fromRank: u.rank,
          toRank: r.rank,
          kind: r.dropped ? "DROP" : r.kept ? "KEEP" : "HOLD",
        },
      });
      if (r.dropped) dropped += 1;
      else if (r.kept) kept += 1;
      else held += 1;
    }
    return { month: key, kept, held, dropped };
  });
}

export async function jobFastStartClose() {
  const day = utcDate();
  return runJob("fastStartClose", `fastStartClose:${day}`, async () => {
    const cutoff = new Date();
    cutoff.setUTCDate(cutoff.getUTCDate() - 14);
    const closed = await prisma.user.count({
      where: { activatedAt: { lte: cutoff } },
    });
    return { day, scannedActivated: closed, note: "FS evaluator already no-ops after day 14" };
  });
}

export async function jobLicenseExpire() {
  const day = utcDate();
  return runJob("licenseExpire", `licenseExpire:${day}`, async () => {
    const now = new Date();
    const res = await prisma.user.updateMany({
      where: { licenseStatus: "ACTIVE", licenseRenewsAt: { lt: now } },
      data: { licenseStatus: "EXPIRED" },
    });
    return { expired: res.count };
  });
}

export async function jobWithdrawExpire() {
  const day = utcDate();
  return runJob("withdrawExpire", `withdrawExpire:${day}`, async () => {
    const now = new Date();
    const stale = await prisma.withdrawal.findMany({
      where: { status: "PENDING", expiresAt: { lt: now } },
    });
    for (const w of stale) {
      await prisma.$transaction(async (tx) => {
        await tx.withdrawal.update({ where: { id: w.id }, data: { status: "EXPIRED" } });
        await postLedger(tx, {
          userId: w.userId,
          wallet: "PENDING",
          direction: "DEBIT",
          amountCents: w.amountCents,
          type: "WITHDRAW_EXPIRE",
          refType: "Withdrawal",
          refId: w.id,
        });
        await postLedger(tx, {
          userId: w.userId,
          wallet: "AVAILABLE",
          direction: "CREDIT",
          amountCents: w.amountCents,
          type: "WITHDRAW_EXPIRE",
          refType: "Withdrawal",
          refId: w.id,
        });
      });
    }
    return { expired: stale.length };
  });
}

export const JOBS = {
  dailyCredits: jobDailyCredits,
  weeklySalary: jobWeeklySalary,
  monthlyRanks: jobMonthlyRanks,
  fastStartClose: jobFastStartClose,
  licenseExpire: jobLicenseExpire,
  withdrawExpire: jobWithdrawExpire,
} as const;

export type JobName = keyof typeof JOBS;
