import { Money } from "../money";
import { nextRank, prevRank, rankByCode, type CompConfig, type RankCode } from "./config";

export type RankMeterState = {
  rank: RankCode;
  psvMeterCents: bigint;
  tvMeterCents: bigint;
  paidOneTime: RankCode[];
};

export type RankPromotion = {
  from: RankCode;
  to: RankCode;
  bonusCents: bigint;
  payBonus: boolean;
  consumedPsvCents: bigint;
  consumedTvCents: bigint;
};

export type RankUpResult = {
  rank: RankCode;
  psvMeterCents: bigint;
  tvMeterCents: bigint;
  promotions: RankPromotion[];
};

/**
 * Rank-up meters: PSV + TV quotas. On hit, consume quota and RESET remainder toward the next rank.
 * Spillover excess PSV/TV applies to the next rank immediately.
 * Monthly KEEP TV is a separate meter and MUST NOT be passed in here.
 */
export function applyRankUpSpillover(cfg: CompConfig, state: RankMeterState): RankUpResult {
  let rank = state.rank;
  let psv = Money.fromCents(state.psvMeterCents);
  let tv = Money.fromCents(state.tvMeterCents);
  const paid = new Set(state.paidOneTime);
  const promotions: RankPromotion[] = [];

  while (true) {
    const nxt = nextRank(cfg, rank);
    if (!nxt || nxt.code === "NONE") break;
    const needPsv = Money.fromCents(nxt.psvCents);
    const needTv = Money.fromCents(nxt.rankUpTvCents);
    if (needPsv.isZero() && needTv.isZero()) break;
    if (psv.gte(needPsv) && tv.gte(needTv)) {
      const payBonus = !paid.has(nxt.code) && nxt.oneTimeCents > 0;
      promotions.push({
        from: rank,
        to: nxt.code,
        bonusCents: payBonus ? BigInt(nxt.oneTimeCents) : 0n,
        payBonus,
        consumedPsvCents: needPsv.cents,
        consumedTvCents: needTv.cents,
      });
      if (payBonus) paid.add(nxt.code);
      psv = psv.sub(needPsv);
      tv = tv.sub(needTv);
      rank = nxt.code;
    } else {
      break;
    }
  }

  return {
    rank,
    psvMeterCents: psv.cents,
    tvMeterCents: tv.cents,
    promotions,
  };
}

export type KeepMonthResult = {
  kept: boolean;
  salaryActive: boolean;
  missStreak: number;
  rank: RankCode;
  rankHeldUntil: Date | null;
  dropped: boolean;
};

/**
 * Monthly KEEP TV is separate and does not reset rank-up meters.
 * Miss 1 month: stop salary, hold rank 30 days.
 * Miss 2nd: drop one rank.
 */
export function applyMonthlyKeep(opts: {
  cfg: CompConfig;
  rank: RankCode;
  keepTvCents: bigint;
  missStreak: number;
  now: Date;
}): KeepMonthResult {
  const def = rankByCode(opts.cfg, opts.rank);
  const need = BigInt(def.keepTvCents);
  if (opts.rank === "NONE" || need === 0n) {
    return {
      kept: true,
      salaryActive: false,
      missStreak: 0,
      rank: opts.rank,
      rankHeldUntil: null,
      dropped: false,
    };
  }
  if (opts.keepTvCents >= need) {
    return {
      kept: true,
      salaryActive: def.weeklyCents > 0,
      missStreak: 0,
      rank: opts.rank,
      rankHeldUntil: null,
      dropped: false,
    };
  }
  const missStreak = opts.missStreak + 1;
  if (missStreak === 1) {
    const held = new Date(opts.now);
    held.setUTCDate(held.getUTCDate() + 30);
    return {
      kept: false,
      salaryActive: false,
      missStreak,
      rank: opts.rank,
      rankHeldUntil: held,
      dropped: false,
    };
  }
  const prev = prevRank(opts.cfg, opts.rank);
  return {
    kept: false,
    salaryActive: false,
    missStreak: 0,
    rank: prev?.code ?? "NONE",
    rankHeldUntil: null,
    dropped: true,
  };
}

export function weeklySalaryCents(cfg: CompConfig, rank: RankCode, salaryActive: boolean): bigint {
  if (!salaryActive) return 0n;
  return BigInt(rankByCode(cfg, rank).weeklyCents);
}
