import { TRPCError } from "@trpc/server";
import { z } from "zod";
import bcrypt from "bcryptjs";
import { prisma } from "../../db";
import { redis } from "../../redis";
import {
  clearLoginFailures,
  consumeBackupCode,
  createUser,
  destroySession,
  encryptTotp,
  generateBackupCodes,
  issueSession,
  loginAllowed,
  noteLoginFailure,
  newTotpSecret,
  totpFromSecret,
  verifyPassword,
  verifyUserTotp,
  SESSION_COOKIE,
} from "../../auth";
import { cookies } from "next/headers";
import { publicProcedure, protectedProcedure, router } from "../init";

function sessionCookie(token: string, maxAge: number) {
  const secure = process.env.NODE_ENV === "production" ? "; Secure" : "";
  return `${SESSION_COOKIE}=${token}; Path=/; HttpOnly; SameSite=Lax; Max-Age=${maxAge}${secure}`;
}

export const authRouter = router({
  register: publicProcedure
    .input(
      z.object({
        email: z.string().email(),
        password: z.string().min(10).max(72),
        sponsorCode: z.string().optional(),
        displayName: z.string().max(80).optional(),
      }),
    )
    .mutation(async ({ input, ctx }) => {
      const exists = await prisma.user.findUnique({ where: { email: input.email.toLowerCase() } });
      if (exists) throw new TRPCError({ code: "CONFLICT", message: "Email already registered" });
      let sponsorId: string | undefined;
      if (input.sponsorCode) {
        const s = await prisma.user.findFirst({
          where: { OR: [{ id: input.sponsorCode }, { email: input.sponsorCode.toLowerCase() }] },
        });
        sponsorId = s?.id;
      }
      const user = await createUser({
        email: input.email,
        password: input.password,
        sponsorId,
        displayName: input.displayName,
      });
      const sess = await issueSession({ userId: user.id, ip: ctx.ip, userAgent: ctx.userAgent });
      const jar = await cookies();
      jar.set(SESSION_COOKIE, sess.token, { httpOnly: true, sameSite: "lax", path: "/", maxAge: 86400 });
      return { userId: user.id };
    }),

  login: publicProcedure
    .input(z.object({ email: z.string().email(), password: z.string(), totp: z.string().optional() }))
    .mutation(async ({ input, ctx }) => {
      const allowed = await loginAllowed(input.email);
      if (!allowed) throw new TRPCError({ code: "TOO_MANY_REQUESTS", message: "Locked out. Try later." });
      const user = await prisma.user.findUnique({ where: { email: input.email.toLowerCase() } });
      if (!user || !(await verifyPassword(input.password, user.passwordHash))) {
        await noteLoginFailure(input.email);
        throw new TRPCError({ code: "UNAUTHORIZED", message: "Invalid credentials" });
      }
      if (user.status === "CLOSED") throw new TRPCError({ code: "FORBIDDEN", message: "Account closed" });
      if (user.totpEnabled) {
        if (!input.totp) throw new TRPCError({ code: "FORBIDDEN", message: "2FA_REQUIRED" });
        const ok = await verifyUserTotp(user, input.totp);
        if (!ok) throw new TRPCError({ code: "UNAUTHORIZED", message: "Invalid 2FA" });
      }
      await clearLoginFailures(input.email);
      const sess = await issueSession({ userId: user.id, ip: ctx.ip, userAgent: ctx.userAgent });
      const jar = await cookies();
      jar.set(SESSION_COOKIE, sess.token, { httpOnly: true, sameSite: "lax", path: "/", maxAge: 86400 });
      return {
        userId: user.id,
        role: user.role,
        totpEnabled: user.totpEnabled,
      };
    }),

  logout: protectedProcedure.mutation(async ({ ctx }) => {
    if (ctx.token) await destroySession(ctx.token);
    const jar = await cookies();
    jar.set(SESSION_COOKIE, "", { httpOnly: true, sameSite: "lax", path: "/", maxAge: 0 });
    return { ok: true };
  }),

  me: publicProcedure.query(async ({ ctx }) => {
    if (!ctx.user) return null;
    const user = await prisma.user.findUnique({
      where: { id: ctx.user.id },
      include: { license: true },
    });
    return user
      ? {
          id: user.id,
          email: user.email,
          role: user.role,
          status: user.status,
          rank: user.rank,
          licenseStatus: user.licenseStatus,
          licenseTier: user.license?.tier ?? null,
          licenseRenewsAt: user.licenseRenewsAt,
          totpEnabled: user.totpEnabled,
          kycStatus: user.kycStatus,
          activatedAt: user.activatedAt,
          impersonatedBy: ctx.user.impersonatedBy,
          sponsorId: user.sponsorId,
        }
      : null;
  }),

  sessions: protectedProcedure.query(async ({ ctx }) => {
    return prisma.session.findMany({
      where: { userId: ctx.user.id, revokedAt: null },
      orderBy: { createdAt: "desc" },
      select: { id: true, ip: true, userAgent: true, createdAt: true, expiresAt: true, impersonatedBy: true },
    });
  }),

  revokeSession: protectedProcedure
    .input(z.object({ id: z.string() }))
    .mutation(async ({ ctx, input }) => {
      await prisma.session.updateMany({
        where: { id: input.id, userId: ctx.user.id },
        data: { revokedAt: new Date() },
      });
      return { ok: true };
    }),

  totpSetup: protectedProcedure.mutation(async ({ ctx }) => {
    const secret = newTotpSecret();
    await redis().set(`totp-setup:${ctx.user.id}`, secret, "EX", 600);
    const totp = totpFromSecret(secret);
    totp.label = ctx.user.email;
    return { secret, uri: totp.toString() };
  }),

  totpVerify: protectedProcedure
    .input(z.object({ code: z.string() }))
    .mutation(async ({ ctx, input }) => {
      const secret = await redis().get(`totp-setup:${ctx.user.id}`);
      if (!secret) throw new TRPCError({ code: "BAD_REQUEST", message: "No setup in progress" });
      const totp = totpFromSecret(secret);
      if (totp.validate({ token: input.code.replace(/\s/g, ""), window: 1 }) === null) {
        throw new TRPCError({ code: "UNAUTHORIZED", message: "Invalid code" });
      }
      await prisma.user.update({
        where: { id: ctx.user.id },
        data: { totpEnabled: true, totpSecretEnc: encryptTotp(secret) },
      });
      await redis().del(`totp-setup:${ctx.user.id}`);
      const codes = await generateBackupCodes(ctx.user.id);
      return { backupCodes: codes };
    }),

  totpDisable: protectedProcedure
    .input(z.object({ code: z.string() }))
    .mutation(async ({ ctx, input }) => {
      const user = await prisma.user.findUniqueOrThrow({ where: { id: ctx.user.id } });
      const ok = await verifyUserTotp(user, input.code);
      if (!ok) throw new TRPCError({ code: "UNAUTHORIZED", message: "Invalid 2FA" });
      await prisma.user.update({
        where: { id: ctx.user.id },
        data: { totpEnabled: false, totpSecretEnc: null },
      });
      return { ok: true };
    }),

  backupCodes: protectedProcedure
    .input(z.object({ code: z.string() }))
    .mutation(async ({ ctx, input }) => {
      const user = await prisma.user.findUniqueOrThrow({ where: { id: ctx.user.id } });
      const ok = await verifyUserTotp(user, input.code);
      if (!ok) throw new TRPCError({ code: "UNAUTHORIZED" });
      return { backupCodes: await generateBackupCodes(ctx.user.id) };
    }),

  changePassword: protectedProcedure
    .input(z.object({ current: z.string(), next: z.string().min(10) }))
    .mutation(async ({ ctx, input }) => {
      const user = await prisma.user.findUniqueOrThrow({ where: { id: ctx.user.id } });
      if (!(await bcrypt.compare(input.current, user.passwordHash))) {
        throw new TRPCError({ code: "UNAUTHORIZED" });
      }
      const passwordHash = await bcrypt.hash(input.next, 12);
      await prisma.user.update({ where: { id: ctx.user.id }, data: { passwordHash } });
      return { ok: true };
    }),

  consumeBackup: publicProcedure
    .input(z.object({ userId: z.string(), code: z.string() }))
    .mutation(async ({ input }) => {
      return { ok: await consumeBackupCode(input.userId, input.code) };
    }),
});
