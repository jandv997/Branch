import { Money } from "../money";
import { licenseByTier, type CompConfig, type LicenseTier } from "./config";

export type DailyCreditInput = {
  principalCents: bigint;
  licenseTier: LicenseTier;
  licenseStatus: "NONE" | "ACTIVE" | "EXPIRED" | "GRACE";
  paused: boolean;
  haltCredits: boolean;
  engineBps: number;
  dailyCapOverrideBps?: number | null;
};

export type DailyCreditResult = {
  engineBps: number;
  licenseCapBps: number;
  appliedBps: number;
  amountCents: bigint;
  reason: string;
};

/**
 * Daily credit = min(engineRate, licenseCap) * principal → EARNINGS.
 * Inactive license = 0. Paused portfolio = 0. Caps are “up to”, never a guaranteed ROI.
 */
export function computeDailyCredit(cfg: CompConfig, input: DailyCreditInput): DailyCreditResult {
  const lic = licenseByTier(cfg, input.licenseTier);
  const licenseCapBps = input.dailyCapOverrideBps ?? lic.dailyCapBps;
  if (input.haltCredits) {
    return { engineBps: input.engineBps, licenseCapBps, appliedBps: 0, amountCents: 0n, reason: "HALT" };
  }
  if (input.paused) {
    return { engineBps: input.engineBps, licenseCapBps, appliedBps: 0, amountCents: 0n, reason: "PAUSED" };
  }
  if (input.licenseStatus !== "ACTIVE") {
    return { engineBps: input.engineBps, licenseCapBps, appliedBps: 0, amountCents: 0n, reason: "INACTIVE_LICENSE" };
  }
  const appliedBps = Math.min(input.engineBps, licenseCapBps);
  const amount = Money.fromCents(input.principalCents).pctBps(appliedBps);
  return {
    engineBps: input.engineBps,
    licenseCapBps,
    appliedBps,
    amountCents: amount.cents,
    reason: "CREDITED",
  };
}

export class EngineProvider {
  constructor(private readonly bps: number) {}
  currentBps(): number {
    return this.bps;
  }
}
