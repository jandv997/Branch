import { describe, expect, it } from "vitest";
import { DEFAULT_COMP_CONFIG } from "@/domain/comp/config";
import { evaluateFastStart, type FastStartInput } from "@/domain/comp/fast-start";
import { clipDepositCompensation } from "@/domain/comp/clip";
import { Money } from "@/domain/money";

const cfg = DEFAULT_COMP_CONFIG;

function base(over: Partial<FastStartInput> = {}): FastStartInput {
  const now = new Date("2026-09-10T00:00:00Z");
  return {
    now,
    earnerId: "sponsor",
    earnerActivatedAt: new Date("2026-09-01T00:00:00Z"),
    depositorId: "new-user",
    genealogicalLevelFromEarner: 1,
    amountCents: 1_000_000n,
    kind: "NEW_PORTFOLIO_DIRECT_DEPOSIT",
    alreadyPaidOnDeposit: false,
    isSelf: false,
    isWash: false,
    window: {
      l1DdCents: 5_000_000n,
      l1l3DdCents: 10_000_000n,
      fundedL1Count: 100,
      fundedL1L3Count: 200,
    },
    treePaidOnThisDepositCents: 0n,
    ...over,
  };
}

describe("Fast Start paths A/B, one-earner, 25% clip", () => {
  it("volume Path A wins over Path B", () => {
    const r = evaluateFastStart(cfg, base({ window: { l1DdCents: 5_000_000n, l1l3DdCents: 10_000_000n, fundedL1Count: 0, fundedL1L3Count: 0 } }));
    expect(r.volumePath).toBe("A");
    expect(r.rawBps).toBe(500);
  });

  it("volume Path B when A misses", () => {
    const r = evaluateFastStart(
      cfg,
      base({
        window: { l1DdCents: 4_999_999n, l1l3DdCents: 10_000_000n, fundedL1Count: 0, fundedL1L3Count: 0 },
      }),
    );
    expect(r.volumePath).toBe("B");
    expect(r.headcountPath).toBeNull();
    expect(r.rawBps).toBe(500);
  });

  it("headcount Path A wins over Path B", () => {
    const r = evaluateFastStart(
      cfg,
      base({
        window: { l1DdCents: 0n, l1l3DdCents: 0n, fundedL1Count: 100, fundedL1L3Count: 200 },
      }),
    );
    expect(r.headcountPath).toBe("A");
    expect(r.rawBps).toBe(500);
  });

  it("headcount Path B when A misses", () => {
    const r = evaluateFastStart(
      cfg,
      base({
        window: { l1DdCents: 0n, l1l3DdCents: 0n, fundedL1Count: 99, fundedL1L3Count: 200 },
      }),
    );
    expect(r.headcountPath).toBe("B");
  });

  it("volume extra + headcount extra may both apply (10% raw → clipped to 7.5%)", () => {
    const r = evaluateFastStart(cfg, base());
    expect(r.volumePath).toBe("A");
    expect(r.headcountPath).toBe("A");
    expect(r.rawBps).toBe(1000);
    expect(r.clippedBps).toBe(750);
    expect(r.clipReason).toBe("FS_RAW_CAP");
    expect(r.amountCents).toBe(Money.fromCents(1_000_000n).pctBps(750).cents);
  });

  it("one earner: already paid on this deposit → $0", () => {
    const r = evaluateFastStart(cfg, base({ alreadyPaidOnDeposit: true }));
    expect(r.eligible).toBe(false);
    expect(r.reason).toBe("ONE_EARNER_ALREADY_PAID");
    expect(r.amountCents).toBe(0n);
  });

  it("excludes self, wash, wallets, staking", () => {
    expect(evaluateFastStart(cfg, base({ isSelf: true })).reason).toBe("SELF");
    expect(evaluateFastStart(cfg, base({ isWash: true })).reason).toBe("WASH");
    expect(evaluateFastStart(cfg, base({ kind: "WALLET_FUNDED_NEW" })).reason).toBe("NOT_DD_VOLUME");
    expect(evaluateFastStart(cfg, base({ kind: "STAKING_MOVE" })).reason).toBe("NOT_DD_VOLUME");
  });

  it("miss gates → FS $0", () => {
    const r = evaluateFastStart(
      cfg,
      base({
        window: { l1DdCents: 0n, l1l3DdCents: 0n, fundedL1Count: 0, fundedL1L3Count: 0 },
      }),
    );
    expect(r.eligible).toBe(false);
    expect(r.reason).toBe("MISS_GATES");
    expect(r.amountCents).toBe(0n);
  });

  it("window is days 1–14 from activatedAt only", () => {
    const r = evaluateFastStart(
      cfg,
      base({
        earnerActivatedAt: new Date("2026-08-01T00:00:00Z"),
      }),
    );
    expect(r.reason).toBe("WINDOW_CLOSED");
  });

  it("tree 17.5% + FS 10% raw clips FS so total ≤ 25%", () => {
    const deposit = 1_000_000n;
    const tree = Money.fromCents(deposit).pctBps(1750).cents;
    const clip = clipDepositCompensation(cfg, {
      depositCents: deposit,
      treePaidCents: tree,
      fastStartRawBps: 1000,
    });
    expect(clip.treeCents).toBe(175_000n);
    expect(clip.fastStartCents + clip.treeCents).toBeLessThanOrEqual(clip.capCents);
    expect(clip.totalCents).toBe(250_000n);
    expect(clip.fastStartCents).toBe(75_000n);
  });

  it("never exceeds 25% of the deposit", () => {
    const clip = clipDepositCompensation(cfg, {
      depositCents: 999_999n,
      treePaidCents: Money.fromCents(999_999n).pctBps(1750).cents,
      fastStartRawBps: 1000,
    });
    expect(clip.totalCents <= clip.capCents).toBe(true);
  });
});
