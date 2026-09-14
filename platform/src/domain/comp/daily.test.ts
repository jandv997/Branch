import { describe, expect, it } from "vitest";
import { DEFAULT_COMP_CONFIG } from "@/domain/comp/config";
import { computeDailyCredit, EngineProvider } from "@/domain/comp/daily";
import { Money } from "@/domain/money";

const cfg = DEFAULT_COMP_CONFIG;

describe("daily credits", () => {
  it("credits min(engine, license cap) * principal to be posted to EARNINGS", () => {
    const engine = new EngineProvider(100);
    const r = computeDailyCredit(cfg, {
      principalCents: 100_000n,
      licenseTier: "PULSE",
      licenseStatus: "ACTIVE",
      paused: false,
      haltCredits: false,
      engineBps: engine.currentBps(),
    });
    expect(r.appliedBps).toBe(50);
    expect(r.amountCents).toBe(Money.fromCents(100_000n).pctBps(50).cents);
    expect(r.reason).toBe("CREDITED");
  });

  it("engine below cap uses engine rate (up to, never guaranteed)", () => {
    const r = computeDailyCredit(cfg, {
      principalCents: 100_000n,
      licenseTier: "PULSE",
      licenseStatus: "ACTIVE",
      paused: false,
      haltCredits: false,
      engineBps: 40,
    });
    expect(r.appliedBps).toBe(40);
  });

  it("inactive license = 0", () => {
    const r = computeDailyCredit(cfg, {
      principalCents: 100_000n,
      licenseTier: "CORE",
      licenseStatus: "EXPIRED",
      paused: false,
      haltCredits: false,
      engineBps: 40,
    });
    expect(r.amountCents).toBe(0n);
    expect(r.reason).toBe("INACTIVE_LICENSE");
  });

  it("paused or halted = 0", () => {
    expect(
      computeDailyCredit(cfg, {
        principalCents: 100_000n,
        licenseTier: "CORE",
        licenseStatus: "ACTIVE",
        paused: true,
        haltCredits: false,
        engineBps: 40,
      }).reason,
    ).toBe("PAUSED");
    expect(
      computeDailyCredit(cfg, {
        principalCents: 100_000n,
        licenseTier: "CORE",
        licenseStatus: "ACTIVE",
        paused: false,
        haltCredits: true,
        engineBps: 40,
      }).reason,
    ).toBe("HALT");
  });
});
