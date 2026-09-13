import { z } from "zod";

/** Rank codes in promotion order. NONE is unranked. */
export const RANK_ORDER = [
  "NONE",
  "ASSOCIATE",
  "CONSULTANT",
  "STRATEGIST",
  "TEAM_LEAD",
  "MANAGER",
  "SENIOR_MANAGER",
  "DIRECTOR",
  "SENIOR_DIRECTOR",
  "EXECUTIVE",
  "AMBASSADOR",
  "SOVEREIGN",
  "LEGACY",
] as const;

export type RankCode = (typeof RANK_ORDER)[number];

export const RankCodeSchema = z.enum(RANK_ORDER);

export const LicenseTierSchema = z.enum(["PULSE", "CORE", "APEX", "PRIME"]);
export type LicenseTier = z.infer<typeof LicenseTierSchema>;

export const FundingSourceSchema = z.enum([
  "DIRECT_DEPOSIT",
  "REFERRAL_WALLET",
  "EARNINGS_WALLET",
  "STAKING_WALLET",
]);
export type FundingSource = z.infer<typeof FundingSourceSchema>;

export const LicenseDefSchema = z.object({
  tier: LicenseTierSchema,
  name: z.string(),
  priceCents: z.number().int().nonnegative(),
  capCents: z.number().int().nonnegative(),
  dailyCapBps: z.number().int().nonnegative(),
  minFundCents: z.number().int().nonnegative(),
  cycleDays: z.number().int().positive(),
});
export type LicenseDef = z.infer<typeof LicenseDefSchema>;

export const RankDefSchema = z.object({
  code: RankCodeSchema,
  name: z.string(),
  psvCents: z.number().int().nonnegative(),
  rankUpTvCents: z.number().int().nonnegative(),
  keepTvCents: z.number().int().nonnegative(),
  oneTimeCents: z.number().int().nonnegative(),
  weeklyCents: z.number().int().nonnegative(),
});
export type RankDef = z.infer<typeof RankDefSchema>;

export const FastStartGatesSchema = z.object({
  windowDays: z.number().int().positive(),
  rawCapBps: z.number().int().nonnegative(),
  volumePathA: z.object({
    thresholdCents: z.number().int().nonnegative(),
    bps: z.number().int().nonnegative(),
    levels: z.number().int().positive(),
  }),
  volumePathB: z.object({
    thresholdCents: z.number().int().nonnegative(),
    bps: z.number().int().nonnegative(),
    levels: z.number().int().positive(),
  }),
  headcountPathA: z.object({
    count: z.number().int().nonnegative(),
    bps: z.number().int().nonnegative(),
    levels: z.number().int().positive(),
  }),
  headcountPathB: z.object({
    count: z.number().int().nonnegative(),
    bps: z.number().int().nonnegative(),
    levels: z.number().int().positive(),
  }),
});

export const CompConfigSchema = z.object({
  patentPending: z.literal(true),
  copy: z.object({
    dailyCapDisclaimer: z.string(),
    volumeDisclaimer: z.string(),
    fastStartOneEarner: z.string(),
  }),
  tree: z.object({
    l1Bps: z.number().int().nonnegative(),
    l2Bps: z.number().int().nonnegative(),
    l3Bps: z.number().int().nonnegative(),
  }),
  depositCompCapBps: z.number().int().nonnegative(),
  fastStart: FastStartGatesSchema,
  licenses: z.array(LicenseDefSchema).min(1),
  ranks: z.array(RankDefSchema).min(1),
  engineDefaultBps: z.number().int().nonnegative(),
  earningsToAvailableEnabled: z.boolean(),
  rankPayoutWallet: z.enum(["AVAILABLE", "EARNINGS"]),
  stakingLockDays: z.number().int().nonnegative(),
  whitelistDelayHours: z.number().int().nonnegative(),
  kycWithdrawThresholdCents: z.number().int().nonnegative(),
  withdrawExpiryHours: z.number().int().positive(),
  cycleDaysDefault: z.number().int().positive(),
  haltCredits: z.boolean(),
  haltWithdraws: z.boolean(),
});

export type CompConfig = z.infer<typeof CompConfigSchema>;

export const DEFAULT_COMP_CONFIG: CompConfig = {
  patentPending: true,
  copy: {
    dailyCapDisclaimer:
      "Daily credits are capped (“up to”) at the lesser of the engine rate and the license daily cap. They are not a guaranteed return.",
    volumeDisclaimer:
      "Personal sales volume (PSV) and team volume (TV) are created only by DIRECT_DEPOSIT funding. Referral, earnings, and staking wallet funding never create PSV, TV, tree commission, or Fast Start.",
    fastStartOneEarner:
      "Only one user is paid Fast Start on any single deposit. The genealogical L1 sponsor is the sole candidate earner.",
  },
  tree: {
    l1Bps: 1000,
    l2Bps: 500,
    l3Bps: 250,
  },
  depositCompCapBps: 2500,
  fastStart: {
    windowDays: 14,
    rawCapBps: 750,
    volumePathA: { thresholdCents: 5_000_000, bps: 500, levels: 1 },
    volumePathB: { thresholdCents: 10_000_000, bps: 500, levels: 3 },
    headcountPathA: { count: 100, bps: 500, levels: 1 },
    headcountPathB: { count: 200, bps: 500, levels: 3 },
  },
  licenses: [
    {
      tier: "PULSE",
      name: "Pulse",
      priceCents: 9900,
      capCents: 500_000,
      dailyCapBps: 50,
      minFundCents: 50_000,
      cycleDays: 365,
    },
    {
      tier: "CORE",
      name: "Core",
      priceCents: 24_900,
      capCents: 2_500_000,
      dailyCapBps: 70,
      minFundCents: 200_000,
      cycleDays: 365,
    },
    {
      tier: "APEX",
      name: "Apex",
      priceCents: 49_900,
      capCents: 10_000_000,
      dailyCapBps: 90,
      minFundCents: 500_000,
      cycleDays: 365,
    },
    {
      tier: "PRIME",
      name: "Prime",
      priceCents: 79_900,
      capCents: 25_000_000,
      dailyCapBps: 110,
      minFundCents: 1_000_000,
      cycleDays: 365,
    },
  ],
  ranks: [
    { code: "NONE", name: "Unranked", psvCents: 0, rankUpTvCents: 0, keepTvCents: 0, oneTimeCents: 0, weeklyCents: 0 },
    { code: "ASSOCIATE", name: "Associate", psvCents: 50_000, rankUpTvCents: 500_000, keepTvCents: 100_000, oneTimeCents: 25_000, weeklyCents: 0 },
    { code: "CONSULTANT", name: "Consultant", psvCents: 100_000, rankUpTvCents: 1_000_000, keepTvCents: 200_000, oneTimeCents: 75_000, weeklyCents: 0 },
    { code: "STRATEGIST", name: "Strategist", psvCents: 200_000, rankUpTvCents: 2_000_000, keepTvCents: 300_000, oneTimeCents: 150_000, weeklyCents: 0 },
    { code: "TEAM_LEAD", name: "Team Lead", psvCents: 500_000, rankUpTvCents: 4_000_000, keepTvCents: 500_000, oneTimeCents: 300_000, weeklyCents: 0 },
    { code: "MANAGER", name: "Manager", psvCents: 800_000, rankUpTvCents: 7_500_000, keepTvCents: 800_000, oneTimeCents: 600_000, weeklyCents: 10_000 },
    { code: "SENIOR_MANAGER", name: "Senior Manager", psvCents: 1_200_000, rankUpTvCents: 15_000_000, keepTvCents: 1_200_000, oneTimeCents: 1_200_000, weeklyCents: 25_000 },
    { code: "DIRECTOR", name: "Director", psvCents: 2_000_000, rankUpTvCents: 30_000_000, keepTvCents: 2_000_000, oneTimeCents: 2_500_000, weeklyCents: 50_000 },
    { code: "SENIOR_DIRECTOR", name: "Senior Director", psvCents: 3_000_000, rankUpTvCents: 50_000_000, keepTvCents: 3_000_000, oneTimeCents: 4_000_000, weeklyCents: 80_000 },
    { code: "EXECUTIVE", name: "Executive", psvCents: 4_000_000, rankUpTvCents: 100_000_000, keepTvCents: 4_000_000, oneTimeCents: 7_500_000, weeklyCents: 125_000 },
    { code: "AMBASSADOR", name: "Ambassador", psvCents: 6_000_000, rankUpTvCents: 200_000_000, keepTvCents: 5_000_000, oneTimeCents: 15_000_000, weeklyCents: 200_000 },
    { code: "SOVEREIGN", name: "Sovereign", psvCents: 8_000_000, rankUpTvCents: 350_000_000, keepTvCents: 7_500_000, oneTimeCents: 25_000_000, weeklyCents: 350_000 },
    { code: "LEGACY", name: "Legacy", psvCents: 10_000_000, rankUpTvCents: 500_000_000, keepTvCents: 10_000_000, oneTimeCents: 50_000_000, weeklyCents: 500_000 },
  ],
  engineDefaultBps: 40,
  earningsToAvailableEnabled: true,
  rankPayoutWallet: "AVAILABLE",
  stakingLockDays: 7,
  whitelistDelayHours: 24,
  kycWithdrawThresholdCents: 100_000,
  withdrawExpiryHours: 72,
  cycleDaysDefault: 365,
  haltCredits: false,
  haltWithdraws: false,
};

export function parseCompConfig(input: unknown): CompConfig {
  return CompConfigSchema.parse(input);
}

export function licenseByTier(cfg: CompConfig, tier: LicenseTier): LicenseDef {
  const found = cfg.licenses.find((l) => l.tier === tier);
  if (!found) throw new Error(`Unknown license tier ${tier}`);
  return found;
}

export function rankByCode(cfg: CompConfig, code: RankCode): RankDef {
  const found = cfg.ranks.find((r) => r.code === code);
  if (!found) throw new Error(`Unknown rank ${code}`);
  return found;
}

export function nextRank(cfg: CompConfig, code: RankCode): RankDef | null {
  const i = RANK_ORDER.indexOf(code);
  if (i < 0 || i >= RANK_ORDER.length - 1) return null;
  return rankByCode(cfg, RANK_ORDER[i + 1]!);
}

export function prevRank(cfg: CompConfig, code: RankCode): RankDef | null {
  const i = RANK_ORDER.indexOf(code);
  if (i <= 0) return null;
  return rankByCode(cfg, RANK_ORDER[i - 1]!);
}

export function treeBpsForLevel(cfg: CompConfig, payLevel: 1 | 2 | 3): number {
  if (payLevel === 1) return cfg.tree.l1Bps;
  if (payLevel === 2) return cfg.tree.l2Bps;
  return cfg.tree.l3Bps;
}

export function treeTotalBps(cfg: CompConfig): number {
  return cfg.tree.l1Bps + cfg.tree.l2Bps + cfg.tree.l3Bps;
}
