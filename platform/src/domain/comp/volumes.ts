import type { UplineNode } from "./tree";
import { createsDirectDepositVolume, type CompEventKind } from "./events";

export type VolumeCredit = {
  userId: string;
  psvCents: bigint;
  tvCents: bigint;
  genealogicalLevel: number;
};

/**
 * PSV = L1 DIRECT_DEPOSIT amounts only (new + DD top-ups).
 * TV  = L1–L7 DIRECT_DEPOSIT amounts only.
 * Exclude: licenses, wallet sources, L8+, the user's own deposit (own book ≠ TV).
 */
export function volumeCreditsForDeposit(opts: {
  kind: CompEventKind;
  amountCents: bigint;
  depositorId: string;
  upline: UplineNode[];
}): VolumeCredit[] {
  if (!createsDirectDepositVolume(opts.kind)) return [];
  const out: VolumeCredit[] = [];
  for (const node of opts.upline) {
    if (node.userId === opts.depositorId) continue;
    if (node.genealogicalLevel < 1 || node.genealogicalLevel > 7) continue;
    const psv = node.genealogicalLevel === 1 ? opts.amountCents : 0n;
    const tv = opts.amountCents;
    out.push({
      userId: node.userId,
      psvCents: psv,
      tvCents: tv,
      genealogicalLevel: node.genealogicalLevel,
    });
  }
  return out;
}

export function ownBookCents(principalCents: bigint): bigint {
  return principalCents;
}
