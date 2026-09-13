import type { Prisma } from "@prisma/client";

export async function writeAudit(
  tx: Prisma.TransactionClient,
  data: {
    actorId?: string | null;
    subjectId?: string | null;
    action: string;
    entity: string;
    entityId?: string;
    before?: Prisma.InputJsonValue;
    after?: Prisma.InputJsonValue;
    reason?: string;
    ip?: string | null;
  },
) {
  return tx.auditLog.create({
    data: {
      actorId: data.actorId ?? undefined,
      subjectId: data.subjectId ?? undefined,
      action: data.action,
      entity: data.entity,
      entityId: data.entityId,
      before: data.before,
      after: data.after,
      reason: data.reason,
      ip: data.ip ?? undefined,
    },
  });
}

export function redact(obj: unknown): unknown {
  if (!obj || typeof obj !== "object") return obj;
  const copy = { ...(obj as Record<string, unknown>) };
  for (const k of Object.keys(copy)) {
    const key = k.toLowerCase();
    if (key.includes("secret") || key.includes("password") || key.includes("totp") || key.includes("backup")) {
      copy[k] = "[REDACTED]";
    }
  }
  return copy;
}
