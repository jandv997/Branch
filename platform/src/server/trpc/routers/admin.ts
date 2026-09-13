import { TRPCError } from "@trpc/server";
import { z } from "zod";
import {
  CompConfigSchema,
  LicenseTierSchema,
  RANK_ORDER,
  clipDepositCompensation,
  evaluateFastStart,
  type RankCode,
} from "@/domain/comp";
import { prisma } from "../../db";
import { getCompConfig, putCompConfig } from "../../comp-config";
import { postLedger } from "../../ledger";
import { writeAudit } from "../../audit";
import { JOBS, type JobName } from "../../../jobs/runner";
import { hashPassword, issueSession, SESSION_COOKIE } from "../../auth";
import { fundPortfolio } from "../../funding";
import { cookies } from "next/headers";
import { financeProcedure, staffProcedure, superProcedure, router } from "../init";
import { mergeActivity } from "@/lib/activity";

const cents = z.string().regex(/^\d+$/).transform((s) => BigInt(s));
const walletKind = z.enum(["AVAILABLE", "EARNINGS", "REFERRAL", "STAKING", "PENDING"]);

export const adminRouter = router({
  users: staffProcedure
    .input(
      z.object({
        q: z.string().optional(),
        take: z.number().default(50),
        cursor: z.string().optional(),
      }),
    )
    .query(async ({ input }) => {
      return prisma.user.findMany({
        where: input.q
          ? { OR: [{ email: { contains: input.q, mode: "insensitive" } }, { id: input.q }] }
          : undefined,
        take: input.take,
        skip: input.cursor ? 1 : 0,
        cursor: input.cursor ? { id: input.cursor } : undefined,
        orderBy: { createdAt: "desc" },
        include: { wallets: true, license: true },
      });
    }),

  user: staffProcedure.input(z.object({ id: z.string() })).query(async ({ input }) => {
    return prisma.user.findUniqueOrThrow({
      where: { id: input.id },
      include: {
        wallets: true,
        license: true,
        portfolios: { include: { fundings: true } },
        ledger: { orderBy: { createdAt: "desc" }, take: 50 },
        withdrawals: { orderBy: { createdAt: "desc" }, take: 20 },
        deposits: { orderBy: { createdAt: "desc" }, take: 20 },
        kycDocs: true,
        sessions: { orderBy: { createdAt: "desc" }, take: 10 },
      },
    });
  }),

  updateUser: superProcedure
    .input(
      z.object({
        id: z.string(),
        email: z.string().email().optional(),
        status: z.enum(["ACTIVE", "FROZEN", "CLOSED"]).optional(),
        notes: z.string().optional(),
        kycStatus: z.enum(["NONE", "PENDING", "APPROVED", "REJECTED"]).optional(),
        displayName: z.string().optional(),
      }),
    )
    .mutation(async ({ ctx, input }) => {
      const before = await prisma.user.findUniqueOrThrow({ where: { id: input.id } });
      const { id, ...data } = input;
      const after = await prisma.user.update({ where: { id }, data });
      await prisma.$transaction((tx) =>
        writeAudit(tx, {
          actorId: ctx.user.id,
          subjectId: id,
          action: "admin.user.update",
          entity: "User",
          entityId: id,
          before: { email: before.email, status: before.status },
          after: { email: after.email, status: after.status },
          ip: ctx.ip,
        }),
      );
      return after;
    }),

  freeze: financeProcedure
    .input(z.object({ id: z.string(), frozen: z.boolean(), reason: z.string() }))
    .mutation(async ({ ctx, input }) => {
      await prisma.user.update({
        where: { id: input.id },
        data: { status: input.frozen ? "FROZEN" : "ACTIVE" },
      });
      await prisma.$transaction((tx) =>
        writeAudit(tx, {
          actorId: ctx.user.id,
          subjectId: input.id,
          action: input.frozen ? "admin.freeze" : "admin.unfreeze",
          entity: "User",
          entityId: input.id,
          reason: input.reason,
          ip: ctx.ip,
        }),
      );
      return { ok: true };
    }),

  setRank: superProcedure
    .input(z.object({ id: z.string(), rank: z.enum(RANK_ORDER), reason: z.string() }))
    .mutation(async ({ ctx, input }) => {
      const before = await prisma.user.findUniqueOrThrow({ where: { id: input.id } });
      await prisma.user.update({ where: { id: input.id }, data: { rank: input.rank as RankCode } });
      await prisma.rankEvent.create({
        data: { userId: input.id, fromRank: before.rank, toRank: input.rank as RankCode, kind: "FORCE" },
      });
      await prisma.$transaction((tx) =>
        writeAudit(tx, {
          actorId: ctx.user.id,
          subjectId: input.id,
          action: "admin.setRank",
          entity: "User",
          entityId: input.id,
          before: { rank: before.rank },
          after: { rank: input.rank },
          reason: input.reason,
          ip: ctx.ip,
        }),
      );
      return { ok: true };
    }),

  setSponsor: superProcedure
    .input(z.object({ id: z.string(), sponsorId: z.string().nullable(), reason: z.string() }))
    .mutation(async ({ ctx, input }) => {
      if (input.sponsorId === input.id) throw new TRPCError({ code: "BAD_REQUEST", message: "Cannot self-sponsor" });
      const before = await prisma.user.findUniqueOrThrow({ where: { id: input.id } });
      await prisma.user.update({ where: { id: input.id }, data: { sponsorId: input.sponsorId } });
      await prisma.$transaction((tx) =>
        writeAudit(tx, {
          actorId: ctx.user.id,
          subjectId: input.id,
          action: "admin.setSponsor",
          entity: "User",
          entityId: input.id,
          before: { sponsorId: before.sponsorId },
          after: { sponsorId: input.sponsorId },
          reason: input.reason,
          ip: ctx.ip,
        }),
      );
      return { ok: true, rebuilt: true };
    }),

  setLicense: superProcedure
    .input(z.object({ id: z.string(), tier: LicenseTierSchema.nullable(), reason: z.string() }))
    .mutation(async ({ ctx, input }) => {
      if (!input.tier) {
        await prisma.user.update({
          where: { id: input.id },
          data: { licenseId: null, licenseStatus: "NONE", licenseRenewsAt: null },
        });
      } else {
        const lic = await prisma.license.findUniqueOrThrow({ where: { tier: input.tier } });
        const renews = new Date();
        renews.setUTCDate(renews.getUTCDate() + 365);
        await prisma.user.update({
          where: { id: input.id },
          data: { licenseId: lic.id, licenseStatus: "ACTIVE", licenseRenewsAt: renews },
        });
      }
      await prisma.$transaction((tx) =>
        writeAudit(tx, {
          actorId: ctx.user.id,
          subjectId: input.id,
          action: "admin.setLicense",
          entity: "User",
          reason: input.reason,
          ip: ctx.ip,
        }),
      );
      return { ok: true };
    }),

  reset2fa: superProcedure
    .input(z.object({ id: z.string(), reason: z.string() }))
    .mutation(async ({ ctx, input }) => {
      await prisma.user.update({
        where: { id: input.id },
        data: { totpEnabled: false, totpSecretEnc: null },
      });
      await prisma.backupCode.deleteMany({ where: { userId: input.id } });
      await prisma.$transaction((tx) =>
        writeAudit(tx, {
          actorId: ctx.user.id,
          subjectId: input.id,
          action: "admin.reset2fa",
          entity: "User",
          reason: input.reason,
          ip: ctx.ip,
        }),
      );
      return { ok: true };
    }),

  setPassword: superProcedure
    .input(z.object({ id: z.string(), password: z.string().min(10), reason: z.string() }))
    .mutation(async ({ ctx, input }) => {
      await prisma.user.update({
        where: { id: input.id },
        data: { passwordHash: await hashPassword(input.password) },
      });
      await prisma.$transaction((tx) =>
        writeAudit(tx, {
          actorId: ctx.user.id,
          subjectId: input.id,
          action: "admin.setPassword",
          entity: "User",
          reason: input.reason,
          ip: ctx.ip,
        }),
      );
      return { ok: true };
    }),

  adjustBalance: financeProcedure
    .input(
      z.object({
        userId: z.string(),
        wallet: walletKind,
        direction: z.enum(["CREDIT", "DEBIT"]),
        amountCents: cents,
        reason: z.string().min(3),
        type: z.string().default("ADMIN_ADJUST"),
      }),
    )
    .mutation(async ({ ctx, input }) => {
      return prisma.$transaction((tx) =>
        postLedger(tx, {
          userId: input.userId,
          wallet: input.wallet,
          direction: input.direction,
          amountCents: input.amountCents,
          type: input.type,
          actorId: ctx.user.id,
          reason: input.reason,
          ip: ctx.ip,
        }),
      );
    }),

  adjustPortfolio: superProcedure
    .input(
      z.object({
        id: z.string(),
        principalCents: cents.optional(),
        capCents: cents.optional(),
        dailyCapBps: z.number().int().optional(),
        paused: z.boolean().optional(),
        status: z.enum(["ACTIVE", "PAUSED", "COMPLETED", "CANCELLED"]).optional(),
        cycleStart: z.date().optional(),
        cycleEnd: z.date().optional(),
        reason: z.string(),
      }),
    )
    .mutation(async ({ ctx, input }) => {
      const { id, reason, ...data } = input;
      const before = await prisma.portfolio.findUniqueOrThrow({ where: { id } });
      const after = await prisma.portfolio.update({ where: { id }, data });
      await prisma.$transaction((tx) =>
        writeAudit(tx, {
          actorId: ctx.user.id,
          action: "admin.adjustPortfolio",
          entity: "Portfolio",
          entityId: id,
          before: { principalCents: before.principalCents.toString() },
          after: { principalCents: after.principalCents.toString() },
          reason,
          ip: ctx.ip,
        }),
      );
      return after;
    }),

  postComp: financeProcedure
    .input(
      z.object({
        userId: z.string(),
        kind: z.enum(["TREE_COMMISSION", "FAST_START", "RANK_BONUS", "SALARY", "DAILY_CREDIT"]),
        amountCents: cents,
        reverse: z.boolean().default(false),
        reason: z.string(),
      }),
    )
    .mutation(async ({ ctx, input }) => {
      const wallet =
        input.kind === "DAILY_CREDIT" ? "EARNINGS" : input.kind === "TREE_COMMISSION" || input.kind === "FAST_START" ? "REFERRAL" : "AVAILABLE";
      const direction = input.reverse ? "DEBIT" : "CREDIT";
      return prisma.$transaction((tx) =>
        postLedger(tx, {
          userId: input.userId,
          wallet,
          direction,
          amountCents: input.amountCents,
          type: input.reverse ? `REVERSE_${input.kind}` : input.kind,
          actorId: ctx.user.id,
          reason: input.reason,
          ip: ctx.ip,
        }),
      );
    }),

  impersonate: superProcedure
    .input(z.object({ userId: z.string() }))
    .mutation(async ({ ctx, input }) => {
      const sess = await issueSession({
        userId: input.userId,
        ip: ctx.ip,
        userAgent: ctx.userAgent,
        impersonatedBy: ctx.user.id,
      });
      const jar = await cookies();
      jar.set(SESSION_COOKIE, sess.token, { httpOnly: true, sameSite: "lax", path: "/", maxAge: 3600 });
      await prisma.$transaction((tx) =>
        writeAudit(tx, {
          actorId: ctx.user.id,
          subjectId: input.userId,
          action: "admin.impersonate",
          entity: "Session",
          ip: ctx.ip,
        }),
      );
      return { ok: true };
    }),

  stopImpersonate: staffProcedure.mutation(async ({ ctx }) => {
    if (!ctx.user.impersonatedBy) return { ok: false };
    const orig = await issueSession({ userId: ctx.user.impersonatedBy, ip: ctx.ip });
    const jar = await cookies();
    jar.set(SESSION_COOKIE, orig.token, { httpOnly: true, sameSite: "lax", path: "/", maxAge: 86400 });
    return { ok: true };
  }),

  ledgerSearch: financeProcedure
    .input(
      z.object({
        userId: z.string().optional(),
        type: z.string().optional(),
        take: z.number().default(100),
      }),
    )
    .query(async ({ input }) => {
      return prisma.ledgerEntry.findMany({
        where: { userId: input.userId, type: input.type },
        orderBy: { createdAt: "desc" },
        take: input.take,
        include: { user: { select: { email: true } } },
      });
    }),

  withdrawals: financeProcedure
    .input(z.object({ status: z.enum(["PENDING", "APPROVED", "REJECTED", "PAID", "CANCELLED", "EXPIRED"]).optional() }))
    .query(async ({ input }) => {
      return prisma.withdrawal.findMany({
        where: { status: input.status },
        orderBy: { createdAt: "asc" },
        include: { user: { select: { email: true, kycStatus: true } } },
      });
    }),

  decideWithdraw: financeProcedure
    .input(
      z.object({
        id: z.string(),
        action: z.enum(["APPROVE", "REJECT", "MARK_PAID"]),
        reason: z.string().optional(),
      }),
    )
    .mutation(async ({ ctx, input }) => {
      const w = await prisma.withdrawal.findUniqueOrThrow({ where: { id: input.id } });
      if (input.action === "APPROVE") {
        await prisma.withdrawal.update({
          where: { id: w.id },
          data: { status: "APPROVED", decidedById: ctx.user.id, decidedAt: new Date() },
        });
      } else if (input.action === "MARK_PAID") {
        await prisma.$transaction(async (tx) => {
          await tx.withdrawal.update({
            where: { id: w.id },
            data: { status: "PAID", paidAt: new Date(), decidedById: ctx.user.id },
          });
          await postLedger(tx, {
            userId: w.userId,
            wallet: "PENDING",
            direction: "DEBIT",
            amountCents: w.amountCents,
            type: "WITHDRAW_PAID",
            refId: w.id,
            actorId: ctx.user.id,
          });
        });
      } else {
        await prisma.$transaction(async (tx) => {
          await tx.withdrawal.update({
            where: { id: w.id },
            data: { status: "REJECTED", rejectReason: input.reason, decidedById: ctx.user.id, decidedAt: new Date() },
          });
          await postLedger(tx, {
            userId: w.userId,
            wallet: "PENDING",
            direction: "DEBIT",
            amountCents: w.amountCents,
            type: "WITHDRAW_REJECT",
            refId: w.id,
            actorId: ctx.user.id,
            reason: input.reason,
          });
          await postLedger(tx, {
            userId: w.userId,
            wallet: "AVAILABLE",
            direction: "CREDIT",
            amountCents: w.amountCents,
            type: "WITHDRAW_REJECT",
            refId: w.id,
            actorId: ctx.user.id,
          });
        });
      }
      return { ok: true };
    }),

  batchWithdraw: financeProcedure
    .input(z.object({ ids: z.array(z.string()), action: z.enum(["APPROVE", "MARK_PAID"]) }))
    .mutation(async ({ ctx, input }) => {
      for (const id of input.ids) {
        const w = await prisma.withdrawal.findUnique({ where: { id } });
        if (!w) continue;
        if (input.action === "APPROVE") {
          await prisma.withdrawal.update({
            where: { id },
            data: { status: "APPROVED", decidedById: ctx.user.id, decidedAt: new Date() },
          });
        }
        if (input.action === "MARK_PAID") {
          await prisma.$transaction(async (tx) => {
            await tx.withdrawal.update({
              where: { id },
              data: { status: "PAID", paidAt: new Date(), decidedById: ctx.user.id },
            });
            await postLedger(tx, {
              userId: w.userId,
              wallet: "PENDING",
              direction: "DEBIT",
              amountCents: w.amountCents,
              type: "WITHDRAW_PAID",
              refId: id,
              actorId: ctx.user.id,
            });
          });
        }
      }
      return { ok: true, count: input.ids.length };
    }),

  deposits: financeProcedure.query(async () => {
    return prisma.deposit.findMany({
      orderBy: { createdAt: "desc" },
      take: 200,
      include: { user: { select: { email: true } } },
    });
  }),

  assignDeposit: financeProcedure
    .input(z.object({ id: z.string(), portfolioId: z.string().optional(), confirm: z.boolean().default(true) }))
    .mutation(async ({ ctx, input }) => {
      const dep = await prisma.deposit.findUniqueOrThrow({ where: { id: input.id } });
      if (input.confirm) {
        await prisma.deposit.update({
          where: { id: dep.id },
          data: { status: "CONFIRMED", assignedById: ctx.user.id, confirmedAt: new Date(), portfolioId: input.portfolioId },
        });
        await fundPortfolio({
          userId: dep.userId,
          amountCents: dep.amountCents,
          source: "DIRECT_DEPOSIT",
          portfolioId: input.portfolioId ?? dep.portfolioId ?? undefined,
          actorId: ctx.user.id,
          ip: ctx.ip,
        });
      }
      return { ok: true };
    }),

  configGet: staffProcedure.query(async () => getCompConfig()),

  configPut: superProcedure.input(CompConfigSchema).mutation(async ({ ctx, input }) => {
    const next = await putCompConfig(input, ctx.user.id);
    await prisma.$transaction((tx) =>
      writeAudit(tx, {
        actorId: ctx.user.id,
        action: "admin.config.put",
        entity: "CompConfig",
        entityId: "singleton",
        ip: ctx.ip,
      }),
    );
    return next;
  }),

  dryRun: staffProcedure
    .input(
      z.object({
        depositCents: cents,
        treePaidCents: cents,
        fastStartRawBps: z.number().int(),
      }),
    )
    .query(async ({ input }) => {
      const cfg = await getCompConfig();
      return clipDepositCompensation(cfg, {
        depositCents: input.depositCents,
        treePaidCents: input.treePaidCents,
        fastStartRawBps: input.fastStartRawBps,
      });
    }),

  fsPreview: staffProcedure
    .input(
      z.object({
        amountCents: cents,
        l1DdCents: cents,
        l1l3DdCents: cents,
        fundedL1Count: z.number(),
        fundedL1L3Count: z.number(),
        treePaidCents: cents,
      }),
    )
    .query(async ({ input }) => {
      const cfg = await getCompConfig();
      return evaluateFastStart(cfg, {
        now: new Date(),
        earnerId: "preview",
        earnerActivatedAt: new Date(),
        depositorId: "dep",
        genealogicalLevelFromEarner: 1,
        amountCents: input.amountCents,
        kind: "NEW_PORTFOLIO_DIRECT_DEPOSIT",
        alreadyPaidOnDeposit: false,
        isSelf: false,
        isWash: false,
        window: {
          l1DdCents: input.l1DdCents,
          l1l3DdCents: input.l1l3DdCents,
          fundedL1Count: input.fundedL1Count,
          fundedL1L3Count: input.fundedL1L3Count,
        },
        treePaidOnThisDepositCents: input.treePaidCents,
      });
    }),

  triggerJob: superProcedure
    .input(z.object({ name: z.enum(["dailyCredits", "weeklySalary", "monthlyRanks", "fastStartClose", "licenseExpire", "withdrawExpire"]) }))
    .mutation(async ({ input }) => {
      const fn = JOBS[input.name as JobName];
      return fn();
    }),

  jobs: staffProcedure.query(async () => {
    return prisma.jobRun.findMany({ orderBy: { startedAt: "desc" }, take: 50, include: { exceptions: true } });
  }),

  halt: superProcedure
    .input(z.object({ haltCredits: z.boolean().optional(), haltWithdraws: z.boolean().optional() }))
    .mutation(async ({ input }) => {
      return prisma.systemHalt.upsert({
        where: { id: "singleton" },
        create: { id: "singleton", haltCredits: input.haltCredits ?? false, haltWithdraws: input.haltWithdraws ?? false },
        update: input,
      });
    }),

  haltGet: staffProcedure.query(async () => {
    return prisma.systemHalt.findUnique({ where: { id: "singleton" } });
  }),

  audit: staffProcedure
    .input(z.object({ q: z.string().optional(), take: z.number().default(100) }))
    .query(async ({ input }) => {
      return prisma.auditLog.findMany({
        where: input.q
          ? {
              OR: [
                { action: { contains: input.q, mode: "insensitive" } },
                { entityId: input.q },
                { subjectId: input.q },
              ],
            }
          : undefined,
        orderBy: { createdAt: "desc" },
        take: input.take,
      });
    }),

  analytics: staffProcedure.query(async () => {
    const users = await prisma.user.groupBy({ by: ["rank"], _count: true });
    const licenses = await prisma.user.count({ where: { licenseStatus: "ACTIVE" } });
    const cfg = await getCompConfig();
    const mrr = await prisma.user.findMany({
      where: { licenseStatus: "ACTIVE" },
      include: { license: true },
    });
    const mrrCents = mrr.reduce((a, u) => a + (u.license ? u.license.priceCents / 12n : 0n), 0n);
    const fundings = await prisma.portfolioFunding.findMany();
    const dd = fundings.filter((f) => f.source === "DIRECT_DEPOSIT").reduce((a, f) => a + f.amountCents, 0n);
    const wallets = fundings.filter((f) => f.source !== "DIRECT_DEPOSIT").reduce((a, f) => a + f.amountCents, 0n);
    const tree = await prisma.ledgerEntry.aggregate({
      where: { type: { in: ["TREE_COMMISSION", "FAST_START"] } },
      _sum: { amountCents: true },
    });
    const liabilities = await prisma.wallet.groupBy({ by: ["kind"], _sum: { balanceCents: true } });
    const fs = await prisma.fastStartPayout.aggregate({ _count: true, _sum: { amountCents: true } });
    return {
      ranks: users,
      activeLicenses: licenses,
      mrrCents,
      dd,
      wallets,
      treeAndFs: tree._sum.amountCents ?? 0n,
      depositCapBps: cfg.depositCompCapBps,
      liabilities,
      fs,
    };
  }),

  cmsList: staffProcedure.query(async () => prisma.cmsPage.findMany()),
  cmsPut: superProcedure
    .input(z.object({ slug: z.string(), title: z.string(), body: z.unknown() }))
    .mutation(async ({ ctx, input }) => {
      return prisma.cmsPage.upsert({
        where: { slug: input.slug },
        create: { slug: input.slug, title: input.title, body: input.body as object, updatedBy: ctx.user.id },
        update: { title: input.title, body: input.body as object, updatedBy: ctx.user.id },
      });
    }),

  kycDecide: financeProcedure
    .input(z.object({ docId: z.string(), status: z.enum(["APPROVED", "REJECTED"]), note: z.string().optional() }))
    .mutation(async ({ ctx, input }) => {
      const doc = await prisma.kycDoc.update({
        where: { id: input.docId },
        data: { status: input.status, note: input.note, decidedAt: new Date(), decidedById: ctx.user.id },
      });
      if (input.status === "APPROVED") {
        await prisma.user.update({ where: { id: doc.userId }, data: { kycStatus: "APPROVED" } });
      }
      return doc;
    }),

  tickets: staffProcedure.query(async () => {
    return prisma.ticket.findMany({ include: { user: { select: { email: true } }, messages: true }, orderBy: { updatedAt: "desc" } });
  }),

  replyTicket: staffProcedure
    .input(z.object({ id: z.string(), body: z.string(), status: z.enum(["OPEN", "PENDING", "CLOSED"]).optional() }))
    .mutation(async ({ ctx, input }) => {
      await prisma.ticketMessage.create({
        data: { ticketId: input.id, authorId: ctx.user.id, body: input.body },
      });
      if (input.status) await prisma.ticket.update({ where: { id: input.id }, data: { status: input.status, assigneeId: ctx.user.id } });
      return { ok: true };
    }),

  fsMonitor: staffProcedure.query(async () => {
    const since = new Date();
    since.setUTCDate(since.getUTCDate() - 14);
    const live = await prisma.user.findMany({
      where: { activatedAt: { gte: since } },
      select: { id: true, email: true, activatedAt: true, rank: true },
    });
    const pays = await prisma.fastStartPayout.findMany({ orderBy: { createdAt: "desc" }, take: 50 });
    return { live, pays };
  }),

  fsForceClose: superProcedure
    .input(z.object({ userId: z.string(), reason: z.string() }))
    .mutation(async ({ ctx, input }) => {
      await prisma.user.update({
        where: { id: input.userId },
        data: { activatedAt: new Date(Date.now() - 15 * 86400000) },
      });
      await prisma.$transaction((tx) =>
        writeAudit(tx, {
          actorId: ctx.user.id,
          subjectId: input.userId,
          action: "admin.fsForceClose",
          entity: "User",
          reason: input.reason,
          ip: ctx.ip,
        }),
      );
      return { ok: true };
    }),

  announcement: superProcedure
    .input(z.object({ title: z.string(), body: z.string(), active: z.boolean().default(true) }))
    .mutation(async ({ input }) => prisma.announcement.create({ data: input })),

  activity: staffProcedure
    .input(z.object({ take: z.number().min(1).max(200).default(80) }).optional())
    .query(async ({ input }) => {
      const take = input?.take ?? 80;
      const [jobs, audits, announcements] = await Promise.all([
        prisma.jobRun.findMany({ orderBy: { startedAt: "desc" }, take: 30 }),
        prisma.auditLog.findMany({ orderBy: { createdAt: "desc" }, take: 40 }),
        prisma.announcement.findMany({ orderBy: { createdAt: "desc" }, take: 20 }),
      ]);
      return mergeActivity(
        [
          ...jobs.map((j) => ({
            id: `job-${j.id}`,
            at: j.startedAt,
            kind: "JOB" as const,
            title: `${j.name} · ${j.status}`,
            detail: j.error ?? JSON.stringify(j.stats ?? {}),
            href: "/admin/jobs",
          })),
          ...audits.map((a) => ({
            id: `aud-${a.id}`,
            at: a.createdAt,
            kind: "AUDIT" as const,
            title: a.action,
            detail: [a.entity, a.entityId, a.reason, a.ip].filter(Boolean).join(" · "),
            href: "/admin/audit",
          })),
          ...announcements.map((a) => ({
            id: `ann-${a.id}`,
            at: a.createdAt,
            kind: "SYSTEM" as const,
            title: a.title,
            detail: a.body,
            href: "/updates",
          })),
        ],
        take,
      );
    }),
});
