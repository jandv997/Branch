import type { CompConfig } from "./config";
import { clipDepositCompensation } from "./clip";
import { fastStartConsiders, type CompEventKind } from "./events";

export type FastStartWindow = {
  l1DdCents: bigint;
  l1l3DdCents: bigint;
  fundedL1Count: number;
  fundedL1L3Count: number;
};

export type FastStartInput = {
  now: Date;
  earnerId: string;
  earnerActivatedAt: Date | null;
  depositorId: string;
  genealogicalLevelFromEarner: number;
  amountCents: bigint;
  kind: CompEventKind;
  alreadyPaidOnDeposit: boolean;
  isSelf: boolean;
  isWash: boolean;
  window: FastStartWindow;
  treePaidOnThisDepositCents: bigint;
};

export type FastStartResult = {
  eligible: boolean;
  reason: string;
  volumePath: "A" | "B" | null;
  headcountPath: "A" | "B" | null;
  rawBps: number;
  clippedBps: number;
  amountCents: bigint;
  clipReason: string | null;
  earnerId: string | null;
};

function daysSince(from: Date, now: Date): number {
  return (now.getTime() - from.getTime()) / (1000 * 60 * 60 * 24);
}

/**
 * Fast Start — days 1–14 from earner.activatedAt only.
 * One earner per deposit: the genealogical L1 sponsor (caller must pass that user).
 * Volume extra + headcount extra may both apply. Path A wins if both A and B of a family hit.
 */
export function evaluateFastStart(cfg: CompConfig, input: FastStartInput): FastStartResult {
  const deny = (reason: string): FastStartResult => ({
    eligible: false,
    reason,
    volumePath: null,
    headcountPath: null,
    rawBps: 0,
    clippedBps: 0,
    amountCents: 0n,
    clipReason: null,
    earnerId: null,
  });

  if (input.alreadyPaidOnDeposit) return deny("ONE_EARNER_ALREADY_PAID");
  if (input.isSelf) return deny("SELF");
  if (input.isWash) return deny("WASH");
  if (!fastStartConsiders(input.kind)) return deny("NOT_DD_VOLUME");
  if (!input.earnerActivatedAt) return deny("NOT_ACTIVATED");
  const elapsed = daysSince(input.earnerActivatedAt, input.now);
  if (elapsed < 0) return deny("WINDOW_NOT_OPEN");
  if (elapsed >= cfg.fastStart.windowDays) return deny("WINDOW_CLOSED");
  if (input.genealogicalLevelFromEarner < 1 || input.genealogicalLevelFromEarner > 3) {
    return deny("OUTSIDE_L1_L3");
  }

  const fs = cfg.fastStart;
  let volumePath: "A" | "B" | null = null;
  let volumeBps = 0;
  if (input.window.l1DdCents >= BigInt(fs.volumePathA.thresholdCents) && input.genealogicalLevelFromEarner <= fs.volumePathA.levels) {
    volumePath = "A";
    volumeBps = fs.volumePathA.bps;
  } else if (
    input.window.l1l3DdCents >= BigInt(fs.volumePathB.thresholdCents) &&
    input.genealogicalLevelFromEarner <= fs.volumePathB.levels
  ) {
    volumePath = "B";
    volumeBps = fs.volumePathB.bps;
  }

  let headcountPath: "A" | "B" | null = null;
  let headcountBps = 0;
  if (input.window.fundedL1Count >= fs.headcountPathA.count && input.genealogicalLevelFromEarner <= fs.headcountPathA.levels) {
    headcountPath = "A";
    headcountBps = fs.headcountPathA.bps;
  } else if (
    input.window.fundedL1L3Count >= fs.headcountPathB.count &&
    input.genealogicalLevelFromEarner <= fs.headcountPathB.levels
  ) {
    headcountPath = "B";
    headcountBps = fs.headcountPathB.bps;
  }

  const rawBps = volumeBps + headcountBps;
  if (rawBps === 0) return deny("MISS_GATES");

  const clipped = clipDepositCompensation(cfg, {
    depositCents: input.amountCents,
    treePaidCents: input.treePaidOnThisDepositCents,
    fastStartRawBps: rawBps,
  });

  if (clipped.fastStartCents === 0n) {
    return {
      eligible: false,
      reason: clipped.clipReason ?? "CLIPPED_TO_ZERO",
      volumePath,
      headcountPath,
      rawBps,
      clippedBps: 0,
      amountCents: 0n,
      clipReason: clipped.clipReason,
      earnerId: input.earnerId,
    };
  }

  return {
    eligible: true,
    reason: "PAID",
    volumePath,
    headcountPath,
    rawBps,
    clippedBps: clipped.fastStartBps,
    amountCents: clipped.fastStartCents,
    clipReason: clipped.clipReason,
    earnerId: input.earnerId,
  };
}
