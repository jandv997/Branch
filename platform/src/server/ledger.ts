import type { LedgerDirection, Prisma, WalletKind } from "@prisma/client";
import { prisma } from "./db";

export type LedgerPost = {
  userId: string;
  wallet: WalletKind;
  direction: LedgerDirection;
  amountCents: bigint;
  type: string;
  refType?: string;
  refId?: string;
  meta?: Prisma.InputJsonValue;
  actorId?: string | null;
  reason?: string;
  ip?: string | null;
};

export async function postLedger(tx: Prisma.TransactionClient, entry: LedgerPost) {
  if (entry.amountCents <= 0n) throw new Error("Ledger amount must be positive");

  await tx.$queryRaw`
    SELECT id FROM "Wallet"
    WHERE "userId" = ${entry.userId} AND kind = ${entry.wallet}::"WalletKind"
    FOR UPDATE
  `;

  const wallet = await tx.wallet.findUnique({
    where: { userId_kind: { userId: entry.userId, kind: entry.wallet } },
  });
  if (!wallet) throw new Error(`Wallet ${entry.wallet} missing for ${entry.userId}`);

  const next =
    entry.direction === "CREDIT"
      ? wallet.balanceCents + entry.amountCents
      : wallet.balanceCents - entry.amountCents;
  if (next < 0n) throw new Error(`INSUFFICIENT_${entry.wallet}`);

  await tx.wallet.update({
    where: { id: wallet.id },
    data: { balanceCents: next },
  });

  const row = await tx.ledgerEntry.create({
    data: {
      userId: entry.userId,
      wallet: entry.wallet,
      direction: entry.direction,
      amountCents: entry.amountCents,
      type: entry.type,
      refType: entry.refType,
      refId: entry.refId,
      meta: entry.meta ?? undefined,
    },
  });

  await tx.auditLog.create({
    data: {
      actorId: entry.actorId ?? entry.userId,
      subjectId: entry.userId,
      action: `ledger.${entry.direction.toLowerCase()}`,
      entity: "LedgerEntry",
      entityId: row.id,
      before: { wallet: entry.wallet, balanceCents: wallet.balanceCents.toString() },
      after: { wallet: entry.wallet, balanceCents: next.toString(), type: entry.type },
      reason: entry.reason ?? entry.type,
      ip: entry.ip ?? undefined,
    },
  });

  return { ledgerId: row.id, balanceCents: next };
}

export async function ensureWallets(tx: Prisma.TransactionClient, userId: string) {
  const kinds: WalletKind[] = ["AVAILABLE", "EARNINGS", "REFERRAL", "STAKING", "PENDING"];
  for (const kind of kinds) {
    await tx.wallet.upsert({
      where: { userId_kind: { userId, kind } },
      create: { userId, kind, balanceCents: 0n },
      update: {},
    });
  }
}

export async function getWallets(userId: string) {
  return prisma.wallet.findMany({ where: { userId }, orderBy: { kind: "asc" } });
}
