import "dotenv/config";
import { describe, expect, it } from "vitest";
import { prisma } from "@/server/db";
import { createUser } from "@/server/auth";
import { ensureSystemRows } from "@/server/comp-config";
import { fundPortfolio, purchaseLicense, moveWallet } from "@/server/funding";
import { postLedger, ensureWallets } from "@/server/ledger";
import { DEFAULT_COMP_CONFIG } from "@/domain/comp/config";

const run = Boolean(process.env.DATABASE_URL);

describe.skipIf(!run)("DB integration: fund → ledger → wallet → withdraw", () => {
  it("passive user: license → DD portfolio → earnings path → withdraw hold", async () => {
    await ensureSystemRows();
    for (const l of DEFAULT_COMP_CONFIG.licenses) {
      await prisma.license.upsert({
        where: { tier: l.tier },
        create: {
          tier: l.tier,
          name: l.name,
          priceCents: BigInt(l.priceCents),
          capCents: BigInt(l.capCents),
          dailyCapBps: l.dailyCapBps,
          minFundCents: BigInt(l.minFundCents),
        },
        update: {},
      });
    }

    const email = `itest.${Date.now()}@qorvex.local`;
    const user = await createUser({ email, password: "Qorvex!demo2026" });
    await purchaseLicense({ userId: user.id, tier: "PULSE" });
    const funded = await fundPortfolio({
      userId: user.id,
      amountCents: 50_000n,
      source: "DIRECT_DEPOSIT",
    });
    expect(funded.isNew).toBe(true);
    expect(funded.principalCents).toBe(50_000n);

    const portfolio = await prisma.portfolio.findUniqueOrThrow({ where: { id: funded.portfolioId } });
    expect(portfolio.principalCents).toBe(50_000n);
    const fundingRows = await prisma.portfolioFunding.findMany({ where: { portfolioId: portfolio.id } });
    expect(fundingRows[0]?.source).toBe("DIRECT_DEPOSIT");

    await prisma.$transaction(async (tx) => {
      await postLedger(tx, {
        userId: user.id,
        wallet: "EARNINGS",
        direction: "CREDIT",
        amountCents: 2_500n,
        type: "DAILY_CREDIT",
        refType: "Portfolio",
        refId: portfolio.id,
      });
    });
    await moveWallet({
      userId: user.id,
      from: "EARNINGS",
      to: "AVAILABLE",
      amountCents: 2_500n,
      type: "EARNINGS_TO_AVAILABLE",
    });

    const available = await prisma.wallet.findUniqueOrThrow({
      where: { userId_kind: { userId: user.id, kind: "AVAILABLE" } },
    });
    expect(available.balanceCents).toBe(2_500n);

    await prisma.$transaction(async (tx) => {
      await postLedger(tx, {
        userId: user.id,
        wallet: "AVAILABLE",
        direction: "DEBIT",
        amountCents: 2_500n,
        type: "WITHDRAW_HOLD",
      });
      await postLedger(tx, {
        userId: user.id,
        wallet: "PENDING",
        direction: "CREDIT",
        amountCents: 2_500n,
        type: "WITHDRAW_HOLD",
      });
    });
    const pending = await prisma.wallet.findUniqueOrThrow({
      where: { userId_kind: { userId: user.id, kind: "PENDING" } },
    });
    expect(pending.balanceCents).toBe(2_500n);
    const ledger = await prisma.ledgerEntry.findMany({ where: { userId: user.id } });
    expect(ledger.length).toBeGreaterThanOrEqual(4);
    const audits = await prisma.auditLog.findMany({ where: { subjectId: user.id } });
    expect(audits.length).toBeGreaterThan(0);
    expect(typeof ensureWallets).toBe("function");
  });
});
