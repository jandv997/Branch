import bcrypt from "bcryptjs";
import { cookies } from "next/headers";
import { Secret, TOTP } from "otpauth";
import type { Role, User } from "@prisma/client";
import { prisma } from "./db";
import { redis } from "./redis";
import { decryptSecret, encryptSecret, randomToken, sha256 } from "./crypto";
import { ensureWallets } from "./ledger";
import { writeAudit } from "./audit";
import { env } from "./env";

export const SESSION_COOKIE = "qv_session";

export type SessionUser = {
  id: string;
  email: string;
  role: Role;
  status: User["status"];
  totpEnabled: boolean;
  impersonatedBy: string | null;
};

export async function hashPassword(plain: string): Promise<string> {
  return bcrypt.hash(plain, 12);
}

export async function verifyPassword(plain: string, hash: string): Promise<boolean> {
  return bcrypt.compare(plain, hash);
}

export async function createUser(opts: {
  email: string;
  password: string;
  sponsorId?: string | null;
  role?: Role;
  displayName?: string;
}) {
  const passwordHash = await hashPassword(opts.password);
  return prisma.$transaction(async (tx) => {
    const user = await tx.user.create({
      data: {
        email: opts.email.toLowerCase().trim(),
        passwordHash,
        sponsorId: opts.sponsorId ?? undefined,
        role: opts.role ?? "USER",
        displayName: opts.displayName,
      },
    });
    await ensureWallets(tx, user.id);
    await writeAudit(tx, {
      actorId: user.id,
      subjectId: user.id,
      action: "user.register",
      entity: "User",
      entityId: user.id,
      after: { email: user.email, sponsorId: user.sponsorId },
    });
    return user;
  });
}

export async function issueSession(opts: {
  userId: string;
  ip?: string | null;
  userAgent?: string | null;
  impersonatedBy?: string | null;
}) {
  const token = randomToken(32);
  const tokenHash = sha256(token);
  const expiresAt = new Date(Date.now() + env().SESSION_TTL_SECONDS * 1000);
  await prisma.session.create({
    data: {
      userId: opts.userId,
      tokenHash,
      ip: opts.ip ?? undefined,
      userAgent: opts.userAgent ?? undefined,
      impersonatedBy: opts.impersonatedBy ?? undefined,
      expiresAt,
    },
  });
  await prisma.ipLog.create({
    data: {
      userId: opts.userId,
      ip: opts.ip ?? "unknown",
      userAgent: opts.userAgent ?? undefined,
      action: opts.impersonatedBy ? "impersonate" : "login",
    },
  });
  await redis().set(`session:${tokenHash}`, opts.userId, "EX", env().SESSION_TTL_SECONDS);
  return { token, expiresAt };
}

export async function destroySession(token: string) {
  const tokenHash = sha256(token);
  await prisma.session.updateMany({
    where: { tokenHash, revokedAt: null },
    data: { revokedAt: new Date() },
  });
  await redis().del(`session:${tokenHash}`);
}

export async function getSessionUser(token: string | undefined | null): Promise<SessionUser | null> {
  if (!token) return null;
  const tokenHash = sha256(token);
  const row = await prisma.session.findUnique({
    where: { tokenHash },
    include: { user: true },
  });
  if (!row || row.revokedAt || row.expiresAt < new Date()) return null;
  if (row.user.status === "CLOSED") return null;
  return {
    id: row.user.id,
    email: row.user.email,
    role: row.user.role,
    status: row.user.status,
    totpEnabled: row.user.totpEnabled,
    impersonatedBy: row.impersonatedBy,
  };
}

export async function readRequestSession(): Promise<SessionUser | null> {
  const jar = await cookies();
  const token = jar.get(SESSION_COOKIE)?.value;
  return getSessionUser(token);
}

export function totpFromSecret(secretBase32: string) {
  return new TOTP({
    issuer: "Qorvex AI",
    label: "Qorvex",
    algorithm: "SHA1",
    digits: 6,
    period: 30,
    secret: Secret.fromBase32(secretBase32),
  });
}

export function newTotpSecret(): string {
  return new Secret({ size: 20 }).base32;
}

export function encryptTotp(secret: string) {
  return encryptSecret(secret);
}

export function decryptTotp(enc: string) {
  return decryptSecret(enc);
}

export async function generateBackupCodes(userId: string): Promise<string[]> {
  const codes = Array.from({ length: 10 }, () => randomToken(4).slice(0, 8).toUpperCase());
  await prisma.$transaction(async (tx) => {
    await tx.backupCode.deleteMany({ where: { userId } });
    for (const code of codes) {
      await tx.backupCode.create({
        data: { userId, codeHash: sha256(code) },
      });
    }
  });
  return codes;
}

export async function consumeBackupCode(userId: string, code: string): Promise<boolean> {
  const row = await prisma.backupCode.findFirst({
    where: { userId, codeHash: sha256(code.toUpperCase()), usedAt: null },
  });
  if (!row) return false;
  await prisma.backupCode.update({ where: { id: row.id }, data: { usedAt: new Date() } });
  return true;
}

export async function verifyUserTotp(user: { totpEnabled: boolean; totpSecretEnc: string | null; id: string }, code: string) {
  if (!user.totpEnabled || !user.totpSecretEnc) return false;
  const totp = totpFromSecret(decryptTotp(user.totpSecretEnc));
  const delta = totp.validate({ token: code.replace(/\s/g, ""), window: 1 });
  if (delta !== null) return true;
  return consumeBackupCode(user.id, code);
}

export function isStaff(role: Role) {
  return role === "SUPPORT" || role === "FINANCE" || role === "SUPERADMIN";
}

export function isFinance(role: Role) {
  return role === "FINANCE" || role === "SUPERADMIN";
}

export function isSuper(role: Role) {
  return role === "SUPERADMIN";
}

const FAIL_KEY = (email: string) => `auth:fail:${email.toLowerCase()}`;

export async function noteLoginFailure(email: string) {
  const k = FAIL_KEY(email);
  const n = await redis().incr(k);
  if (n === 1) await redis().expire(k, 900);
  return n;
}

export async function loginAllowed(email: string) {
  const n = Number((await redis().get(FAIL_KEY(email))) ?? "0");
  return n < 8;
}

export async function clearLoginFailures(email: string) {
  await redis().del(FAIL_KEY(email));
}
