import { describe, expect, it } from "vitest";
import { classifyFunding } from "@/domain/comp/events";
import { volumeCreditsForDeposit } from "@/domain/comp/volumes";
import type { UplineNode } from "@/domain/comp/tree";

function upline(levels: number): UplineNode[] {
  return Array.from({ length: levels }, (_, i) => ({
    userId: `u${i + 1}`,
    genealogicalLevel: i + 1,
    activeForPay: i % 2 === 0,
    status: "ACTIVE" as const,
  }));
}

describe("PSV L1 vs TV L1–L7 vs L8 ignored", () => {
  it("DD new portfolio credits PSV only to L1 and TV to L1–L7", () => {
    const credits = volumeCreditsForDeposit({
      kind: classifyFunding("DIRECT_DEPOSIT", true),
      amountCents: 10_000n,
      depositorId: "me",
      upline: upline(8),
    });
    expect(credits).toHaveLength(7);
    expect(credits.find((c) => c.genealogicalLevel === 1)?.psvCents).toBe(10_000n);
    expect(credits.filter((c) => c.genealogicalLevel > 1).every((c) => c.psvCents === 0n)).toBe(true);
    expect(credits.every((c) => c.tvCents === 10_000n)).toBe(true);
    expect(credits.some((c) => c.genealogicalLevel === 8)).toBe(false);
  });

  it("DD top-ups create PSV/TV", () => {
    const credits = volumeCreditsForDeposit({
      kind: classifyFunding("DIRECT_DEPOSIT", false),
      amountCents: 5_000n,
      depositorId: "me",
      upline: upline(2),
    });
    expect(credits[0]?.psvCents).toBe(5_000n);
    expect(credits[1]?.tvCents).toBe(5_000n);
    expect(credits[1]?.psvCents).toBe(0n);
  });

  it("wallet sources create zero volume even if new portfolio", () => {
    for (const src of ["REFERRAL_WALLET", "EARNINGS_WALLET", "STAKING_WALLET"] as const) {
      const credits = volumeCreditsForDeposit({
        kind: classifyFunding(src, true),
        amountCents: 50_000n,
        depositorId: "me",
        upline: upline(3),
      });
      expect(credits).toEqual([]);
    }
  });

  it("license fees are not volume", () => {
    expect(
      volumeCreditsForDeposit({
        kind: "LICENSE_FIRST",
        amountCents: 9900n,
        depositorId: "me",
        upline: upline(3),
      }),
    ).toEqual([]);
  });

  it("own deposit is not TV for the depositor (own book ≠ TV)", () => {
    const credits = volumeCreditsForDeposit({
      kind: "NEW_PORTFOLIO_DIRECT_DEPOSIT",
      amountCents: 10_000n,
      depositorId: "me",
      upline: [
        {
          userId: "me",
          genealogicalLevel: 1,
          activeForPay: true,
          status: "ACTIVE",
        },
        {
          userId: "sponsor",
          genealogicalLevel: 1,
          activeForPay: true,
          status: "ACTIVE",
        },
      ],
    });
    expect(credits.some((c) => c.userId === "me")).toBe(false);
    expect(credits.find((c) => c.userId === "sponsor")?.tvCents).toBe(10_000n);
  });

  it("inactive compression does NOT skip volume — volume always flows", () => {
    const credits = volumeCreditsForDeposit({
      kind: "NEW_PORTFOLIO_DIRECT_DEPOSIT",
      amountCents: 1_000n,
      depositorId: "leaf",
      upline: [
        { userId: "dead", genealogicalLevel: 1, activeForPay: false, status: "FROZEN" },
        { userId: "live", genealogicalLevel: 2, activeForPay: true, status: "ACTIVE" },
      ],
    });
    expect(credits.find((c) => c.userId === "dead")?.psvCents).toBe(1_000n);
    expect(credits.find((c) => c.userId === "live")?.tvCents).toBe(1_000n);
  });
});
