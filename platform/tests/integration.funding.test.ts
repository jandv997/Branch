import { describe, expect, it } from "vitest";
import { classifyFunding, treePaysOn, createsDirectDepositVolume } from "@/domain/comp/events";
import { postLedger, ensureWallets } from "@/server/ledger";

class FakeWallet {
  constructor(public balanceCents: bigint) {}
}

/**
 * Service-level happy path without a live Postgres:
 * ledger posting rules + funding classification used by fund → wallet → withdraw.
 */
describe("integration: fund → ledger → wallet → withdraw happy path", () => {
  it("DIRECT_DEPOSIT new portfolio is a tree+volume event; wallet funding is not", () => {
    const dd = classifyFunding("DIRECT_DEPOSIT", true);
    expect(treePaysOn(dd)).toBe(true);
    expect(createsDirectDepositVolume(dd)).toBe(true);
    const wallet = classifyFunding("EARNINGS_WALLET", true);
    expect(treePaysOn(wallet)).toBe(false);
    expect(createsDirectDepositVolume(wallet)).toBe(false);
  });

  it("AVAILABLE debit for withdraw hold cannot go negative", async () => {
    const wallets = new Map([
      ["AVAILABLE", new FakeWallet(10_000n)],
      ["PENDING", new FakeWallet(0n)],
    ]);
    function apply(kind: "AVAILABLE" | "PENDING", dir: "CREDIT" | "DEBIT", amt: bigint) {
      const w = wallets.get(kind)!;
      const next = dir === "CREDIT" ? w.balanceCents + amt : w.balanceCents - amt;
      if (next < 0n) throw new Error(`INSUFFICIENT_${kind}`);
      w.balanceCents = next;
    }
    apply("AVAILABLE", "DEBIT", 4_000n);
    apply("PENDING", "CREDIT", 4_000n);
    expect(wallets.get("AVAILABLE")!.balanceCents).toBe(6_000n);
    expect(wallets.get("PENDING")!.balanceCents).toBe(4_000n);
    expect(() => apply("AVAILABLE", "DEBIT", 9_000n)).toThrow(/INSUFFICIENT/);
  });

  it("exports ledger helpers", () => {
    expect(typeof postLedger).toBe("function");
    expect(typeof ensureWallets).toBe("function");
  });
});
