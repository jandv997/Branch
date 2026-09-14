import type { LicenseStatus, User, UserStatus } from "@prisma/client";
import { prisma } from "./db";
import { isPayActive, type UplineNode } from "@/domain/comp/tree";

export async function walkUpline(userId: string, maxLevel = 12): Promise<(UplineNode & { raw: User })[]> {
  const out: (UplineNode & { raw: User })[] = [];
  let current = await prisma.user.findUnique({ where: { id: userId } });
  let level = 0;
  const seen = new Set<string>([userId]);
  while (current?.sponsorId && level < maxLevel) {
    if (seen.has(current.sponsorId)) break;
    seen.add(current.sponsorId);
    const parent = await prisma.user.findUnique({ where: { id: current.sponsorId } });
    if (!parent) break;
    level += 1;
    out.push({
      userId: parent.id,
      genealogicalLevel: level,
      activeForPay: isPayActive({
        userStatus: parent.status as UserStatus,
        licenseStatus: parent.licenseStatus as LicenseStatus,
      }),
      status: parent.status,
      raw: parent,
    });
    current = parent;
  }
  return out;
}

export async function rebuildSubtree(_userId: string) {
  return { ok: true as const };
}

export function monthKey(d = new Date()): string {
  return `${d.getUTCFullYear()}-${String(d.getUTCMonth() + 1).padStart(2, "0")}`;
}
