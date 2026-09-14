import { describe, expect, it } from "vitest";
import { DEFAULT_COMP_CONFIG } from "@/domain/comp/config";
import { applyMonthlyKeep, applyRankUpSpillover } from "@/domain/comp/ranks";

const cfg = DEFAULT_COMP_CONFIG;

describe("rank reset + spillover", () => {
  it("consumes Associate quota and spills remainder into Consultant meter", () => {
    const r = applyRankUpSpillover(cfg, {
      rank: "NONE",
      psvMeterCents: 120_000n,
      tvMeterCents: 800_000n,
      paidOneTime: [],
    });
    expect(r.rank).toBe("ASSOCIATE");
    expect(r.psvMeterCents).toBe(70_000n);
    expect(r.tvMeterCents).toBe(300_000n);
    expect(r.promotions).toHaveLength(1);
    expect(r.promotions[0]?.to).toBe("ASSOCIATE");
    expect(r.promotions[0]?.payBonus).toBe(true);
    expect(r.promotions[0]?.bonusCents).toBe(25_000n);
  });

  it("cascades multiple ranks when spillover covers the next quota", () => {
    const r = applyRankUpSpillover(cfg, {
      rank: "NONE",
      psvMeterCents: 200_000n,
      tvMeterCents: 2_000_000n,
      paidOneTime: [],
    });
    expect(r.rank).toBe("CONSULTANT");
    expect(r.promotions.map((p) => p.to)).toEqual(["ASSOCIATE", "CONSULTANT"]);
    expect(r.psvMeterCents).toBe(50_000n);
    expect(r.tvMeterCents).toBe(500_000n);
  });

  it("one-time bonus never pays twice for the same rank", () => {
    const r = applyRankUpSpillover(cfg, {
      rank: "NONE",
      psvMeterCents: 50_000n,
      tvMeterCents: 500_000n,
      paidOneTime: ["ASSOCIATE"],
    });
    expect(r.rank).toBe("ASSOCIATE");
    expect(r.promotions[0]?.payBonus).toBe(false);
    expect(r.promotions[0]?.bonusCents).toBe(0n);
  });

  it("does not promote when only PSV or only TV is met", () => {
    const psvOnly = applyRankUpSpillover(cfg, {
      rank: "NONE",
      psvMeterCents: 1_000_000n,
      tvMeterCents: 0n,
      paidOneTime: [],
    });
    expect(psvOnly.rank).toBe("NONE");
    const tvOnly = applyRankUpSpillover(cfg, {
      rank: "NONE",
      psvMeterCents: 0n,
      tvMeterCents: 10_000_000n,
      paidOneTime: [],
    });
    expect(tvOnly.rank).toBe("NONE");
  });

  it("monthly keep miss 1: stop salary, hold rank 30 days; meters are not this function", () => {
    const now = new Date("2026-09-01T00:00:00Z");
    const miss1 = applyMonthlyKeep({
      cfg,
      rank: "MANAGER",
      keepTvCents: 0n,
      missStreak: 0,
      now,
    });
    expect(miss1.salaryActive).toBe(false);
    expect(miss1.rank).toBe("MANAGER");
    expect(miss1.dropped).toBe(false);
    expect(miss1.rankHeldUntil?.toISOString().slice(0, 10)).toBe("2026-10-01");

    const miss2 = applyMonthlyKeep({
      cfg,
      rank: "MANAGER",
      keepTvCents: 0n,
      missStreak: 1,
      now,
    });
    expect(miss2.dropped).toBe(true);
    expect(miss2.rank).toBe("TEAM_LEAD");
  });

  it("meeting keep TV keeps salary on for ranks with weekly pay", () => {
    const r = applyMonthlyKeep({
      cfg,
      rank: "MANAGER",
      keepTvCents: 800_000n,
      missStreak: 2,
      now: new Date(),
    });
    expect(r.kept).toBe(true);
    expect(r.salaryActive).toBe(true);
    expect(r.missStreak).toBe(0);
  });
});
