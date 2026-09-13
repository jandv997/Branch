import { Money } from "../money";
import { treeBpsForLevel, type CompConfig } from "./config";

export type UplineNode = {
  userId: string;
  genealogicalLevel: number;
  activeForPay: boolean;
  status: "ACTIVE" | "FROZEN" | "CLOSED";
};

export type CompressedPaySlot = {
  userId: string;
  payLevel: 1 | 2 | 3;
  genealogicalLevel: number;
  compressed: boolean;
  bps: number;
  amountCents: bigint;
};

/**
 * Compression applies to PAY only: skip inactive for L1–L3 commission.
 * Volume always flows on genealogical levels (see volumes.ts) — this function is pay-only.
 */
export function compressPayUplines(chain: UplineNode[], cfg: CompConfig, base: Money): CompressedPaySlot[] {
  const slots: CompressedPaySlot[] = [];
  for (const node of chain) {
    if (slots.length >= 3) break;
    if (node.status !== "ACTIVE") continue;
    if (!node.activeForPay) continue;
    const payLevel = (slots.length + 1) as 1 | 2 | 3;
    const bps = treeBpsForLevel(cfg, payLevel);
    slots.push({
      userId: node.userId,
      payLevel,
      genealogicalLevel: node.genealogicalLevel,
      compressed: node.genealogicalLevel !== payLevel,
      bps,
      amountCents: base.pctBps(bps).cents,
    });
  }
  return slots;
}

/** Genealogical ancestors at levels 1..maxLevel, no skip. */
export function volumeUplines(chain: UplineNode[], maxLevel = 7): UplineNode[] {
  return chain.filter((n) => n.genealogicalLevel >= 1 && n.genealogicalLevel <= maxLevel);
}

export function isPayActive(opts: {
  userStatus: "ACTIVE" | "FROZEN" | "CLOSED";
  licenseStatus: "NONE" | "ACTIVE" | "EXPIRED" | "GRACE";
}): boolean {
  return opts.userStatus === "ACTIVE" && opts.licenseStatus === "ACTIVE";
}
