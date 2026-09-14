import { describe, expect, it } from "vitest";
import { DEFAULT_COMP_CONFIG } from "@/domain/comp/config";
import { classifyFunding, treePaysOn, whyTreeNotPaid } from "@/domain/comp/events";
import { compressPayUplines, isPayActive, type UplineNode } from "@/domain/comp/tree";
import { Money } from "@/domain/money";

const cfg = DEFAULT_COMP_CONFIG;

function chain(nodes: Array<Partial<UplineNode> & { userId: string; genealogicalLevel: number }>): UplineNode[] {
  return nodes.map((n) => ({
    userId: n.userId,
    genealogicalLevel: n.genealogicalLevel,
    activeForPay: n.activeForPay ?? true,
    status: n.status ?? "ACTIVE",
  }));
}

describe("tree pay / no-pay matrix", () => {
  it("pays on NEW PORTFOLIO + DIRECT_DEPOSIT", () => {
    const kind = classifyFunding("DIRECT_DEPOSIT", true);
    expect(kind).toBe("NEW_PORTFOLIO_DIRECT_DEPOSIT");
    expect(treePaysOn(kind)).toBe(true);
    expect(whyTreeNotPaid(kind)).toBeNull();
  });

  it("does not pay on DD top-up", () => {
    const kind = classifyFunding("DIRECT_DEPOSIT", false);
    expect(kind).toBe("TOP_UP_DIRECT_DEPOSIT");
    expect(treePaysOn(kind)).toBe(false);
    expect(whyTreeNotPaid(kind)).toBe("TOP_UP");
  });

  it("does not pay on wallet-funded new portfolio", () => {
    for (const src of ["REFERRAL_WALLET", "EARNINGS_WALLET", "STAKING_WALLET"] as const) {
      const kind = classifyFunding(src, true);
      expect(treePaysOn(kind)).toBe(false);
      expect(whyTreeNotPaid(kind)).toBe("WALLET_SOURCE");
    }
  });

  it("does not pay on wallet-funded top-up", () => {
    for (const src of ["REFERRAL_WALLET", "EARNINGS_WALLET", "STAKING_WALLET"] as const) {
      expect(treePaysOn(classifyFunding(src, false))).toBe(false);
    }
  });

  it("pays once on first-ever license fee, not renewals", () => {
    expect(treePaysOn("LICENSE_FIRST")).toBe(true);
    expect(treePaysOn("LICENSE_RENEWAL")).toBe(false);
    expect(whyTreeNotPaid("LICENSE_RENEWAL")).toBe("LICENSE_RENEWAL");
  });

  it("does not pay on daily credits", () => {
    expect(treePaysOn("DAILY_CREDIT")).toBe(false);
    expect(whyTreeNotPaid("DAILY_CREDIT")).toBe("DAILY_CREDIT");
  });

  it("pays L1 10% / L2 5% / L3 2.5% = 17.5% on a new DD portfolio", () => {
    const base = Money.fromDollars(1000);
    const upline = chain([
      { userId: "s1", genealogicalLevel: 1 },
      { userId: "s2", genealogicalLevel: 2 },
      { userId: "s3", genealogicalLevel: 3 },
    ]);
    const slots = compressPayUplines(upline, cfg, base);
    expect(slots.map((s) => s.bps)).toEqual([1000, 500, 250]);
    const total = slots.reduce((a, s) => a + s.amountCents, 0n);
    expect(total).toBe(base.pctBps(1750).cents);
    expect(total).toBe(17_500n);
  });

  it("same user, another NEW portfolio with DD → tree pays again", () => {
    expect(treePaysOn(classifyFunding("DIRECT_DEPOSIT", true))).toBe(true);
    expect(treePaysOn(classifyFunding("DIRECT_DEPOSIT", true))).toBe(true);
  });
});

describe("inactive compression on PAY only", () => {
  it("skips inactive L1 and assigns 10% to next active (compressed)", () => {
    const base = Money.fromDollars(1000);
    const upline = chain([
      { userId: "inactive", genealogicalLevel: 1, activeForPay: false },
      { userId: "active-l2", genealogicalLevel: 2, activeForPay: true },
      { userId: "active-l3", genealogicalLevel: 3, activeForPay: true },
    ]);
    const slots = compressPayUplines(upline, cfg, base);
    expect(slots).toHaveLength(2);
    expect(slots[0]).toMatchObject({ userId: "active-l2", payLevel: 1, bps: 1000, compressed: true });
    expect(slots[1]).toMatchObject({ userId: "active-l3", payLevel: 2, bps: 500, compressed: true });
    expect(slots[0]!.amountCents).toBe(10_000n);
  });

  it("skips frozen/closed even if license flagged active", () => {
    const upline = chain([
      { userId: "frozen", genealogicalLevel: 1, activeForPay: true, status: "FROZEN" },
      { userId: "ok", genealogicalLevel: 2, activeForPay: true },
    ]);
    const slots = compressPayUplines(upline, cfg, Money.fromDollars(100));
    expect(slots[0]?.userId).toBe("ok");
    expect(slots[0]?.payLevel).toBe(1);
  });

  it("isPayActive is license ACTIVE + user ACTIVE", () => {
    expect(isPayActive({ userStatus: "ACTIVE", licenseStatus: "ACTIVE" })).toBe(true);
    expect(isPayActive({ userStatus: "ACTIVE", licenseStatus: "EXPIRED" })).toBe(false);
    expect(isPayActive({ userStatus: "FROZEN", licenseStatus: "ACTIVE" })).toBe(false);
  });
});
