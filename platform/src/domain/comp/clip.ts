import { Money } from "../money";
import type { CompConfig } from "./config";

export type ClipInput = {
  depositCents: bigint;
  treePaidCents: bigint;
  fastStartRawBps: number;
};

export type ClipResult = {
  treeCents: bigint;
  fastStartCents: bigint;
  fastStartBps: number;
  totalCents: bigint;
  capCents: bigint;
  clipReason: string | null;
};

/**
 * CLIP: tree + FS ≤ 25% of that deposit; if FS raw > 7.5%, FS = 7.5%.
 * Tree is never reduced by the clip — Fast Start absorbs the residual.
 */
export function clipDepositCompensation(cfg: CompConfig, input: ClipInput): ClipResult {
  const deposit = Money.fromCents(input.depositCents);
  const cap = deposit.pctBps(cfg.depositCompCapBps);
  const tree = Money.fromCents(input.treePaidCents);

  let fsBps = input.fastStartRawBps;
  let clipReason: string | null = null;
  if (fsBps > cfg.fastStart.rawCapBps) {
    fsBps = cfg.fastStart.rawCapBps;
    clipReason = "FS_RAW_CAP";
  }

  let fs = deposit.pctBps(fsBps);
  if (tree.add(fs).gt(cap)) {
    fs = cap.cents > tree.cents ? cap.sub(tree) : Money.ZERO;
    fsBps = deposit.cents === 0n ? 0 : Number((fs.cents * 10_000n) / deposit.cents);
    clipReason = clipReason ? `${clipReason}+DEPOSIT_25_CAP` : "DEPOSIT_25_CAP";
  }

  const total = tree.add(fs);
  if (total.gt(cap)) {
    throw new Error("Invariant violated: tree + FS exceeds 25% deposit cap");
  }

  return {
    treeCents: tree.cents,
    fastStartCents: fs.cents,
    fastStartBps: fsBps,
    totalCents: total.cents,
    capCents: cap.cents,
    clipReason,
  };
}
