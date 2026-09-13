import "dotenv/config";
import bcrypt from "bcryptjs";
import { DEFAULT_COMP_CONFIG } from "../src/domain/comp/config";
import { prisma } from "../src/server/db";
import { ensureWallets } from "../src/server/ledger";
import { ensureSystemRows } from "../src/server/comp-config";
import { fundPortfolio, purchaseLicense } from "../src/server/funding";

const password = process.env.SEED_PASSWORD ?? "Qorvex!demo2026";

async function mkUser(opts: {
  email: string;
  role?: "USER" | "SUPPORT" | "FINANCE" | "SUPERADMIN";
  sponsorId?: string;
  name?: string;
}) {
  const existing = await prisma.user.findUnique({ where: { email: opts.email } });
  if (existing) return existing;
  const user = await prisma.user.create({
    data: {
      email: opts.email,
      passwordHash: await bcrypt.hash(password, 10),
      role: opts.role ?? "USER",
      displayName: opts.name ?? opts.email.split("@")[0],
      sponsorId: opts.sponsorId,
    },
  });
  await prisma.$transaction((tx) => ensureWallets(tx, user.id));
  return user;
}

async function seedLicenses() {
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
      update: {
        name: l.name,
        priceCents: BigInt(l.priceCents),
        capCents: BigInt(l.capCents),
        dailyCapBps: l.dailyCapBps,
        minFundCents: BigInt(l.minFundCents),
      },
    });
  }
}

async function main() {
  await ensureSystemRows();
  await seedLicenses();

  const superadmin = await mkUser({ email: "superadmin@qorvex.local", role: "SUPERADMIN", name: "Super Admin" });
  const finance = await mkUser({ email: "finance@qorvex.local", role: "FINANCE", name: "Finance Admin" });
  const support = await mkUser({ email: "support@qorvex.local", role: "SUPPORT", name: "Support Admin" });

  const emails = [
    "demo.l0@qorvex.local",
    "demo.l1@qorvex.local",
    "demo.l2@qorvex.local",
    "demo.l3@qorvex.local",
    "demo.l4@qorvex.local",
    "demo.l5@qorvex.local",
    "demo.l6@qorvex.local",
    "demo.l7@qorvex.local",
  ];
  const tree: Awaited<ReturnType<typeof mkUser>>[] = [];
  for (let i = 0; i < emails.length; i++) {
    tree.push(
      await mkUser({
        email: emails[i]!,
        sponsorId: i === 0 ? undefined : tree[i - 1]?.id,
        name: `Demo L${i}`,
      }),
    );
  }

  const sibling = await mkUser({
    email: "demo.l1b@qorvex.local",
    sponsorId: tree[0]!.id,
    name: "Demo L1 sibling",
  });

  const tiers = ["PULSE", "CORE", "APEX", "PRIME", "PULSE", "CORE", "APEX", "PRIME"] as const;
  for (let i = 0; i < tree.length; i++) {
    const u = tree[i]!;
    if (u.licenseStatus !== "ACTIVE") {
      await purchaseLicense({ userId: u.id, tier: tiers[i]!, actorId: superadmin.id });
    }
  }
  if (sibling.licenseStatus !== "ACTIVE") {
    await purchaseLicense({ userId: sibling.id, tier: "CORE", actorId: superadmin.id });
  }

  async function fundIfEmpty(userId: string, amountCents: bigint, source: "DIRECT_DEPOSIT" | "REFERRAL_WALLET" | "EARNINGS_WALLET" | "STAKING_WALLET", precredit?: { kind: "EARNINGS" | "REFERRAL" | "STAKING"; amount: bigint }) {
    const n = await prisma.portfolio.count({ where: { userId } });
    if (n > 0) return;
    if (precredit) {
      await prisma.wallet.update({
        where: { userId_kind: { userId, kind: precredit.kind } },
        data: { balanceCents: { increment: precredit.amount } },
      });
    }
    await fundPortfolio({ userId, amountCents, source });
  }

  await fundIfEmpty(tree[0]!.id, 50_000n, "DIRECT_DEPOSIT");
  await fundIfEmpty(tree[1]!.id, 200_000n, "DIRECT_DEPOSIT");
  await fundIfEmpty(tree[2]!.id, 500_000n, "EARNINGS_WALLET", { kind: "EARNINGS", amount: 500_000n });
  await fundIfEmpty(tree[3]!.id, 1_000_000n, "DIRECT_DEPOSIT");
  await fundIfEmpty(tree[4]!.id, 50_000n, "STAKING_WALLET", { kind: "STAKING", amount: 50_000n });
  await fundIfEmpty(tree[5]!.id, 200_000n, "DIRECT_DEPOSIT");
  await fundIfEmpty(tree[6]!.id, 500_000n, "REFERRAL_WALLET", { kind: "REFERRAL", amount: 500_000n });
  await fundIfEmpty(tree[7]!.id, 1_000_000n, "DIRECT_DEPOSIT");
  await fundIfEmpty(sibling.id, 200_000n, "DIRECT_DEPOSIT");

  await prisma.cmsPage.upsert({
    where: { slug: "faq" },
    create: {
      slug: "faq",
      title: "FAQ",
      body: [
        { q: "Are daily credits guaranteed?", a: "No. They are capped (“up to”) and never a guaranteed ROI." },
      ],
    },
    update: {},
  });

  console.log("Seeded Qorvex AI");
  console.log("  superadmin@qorvex.local /", password);
  console.log("  finance@qorvex.local /", password);
  console.log("  support@qorvex.local /", password);
  console.log("  7-level demo tree demo.l0 … demo.l7@qorvex.local");
  console.log({ superadmin: superadmin.email, finance: finance.email, support: support.email });
}

main()
  .then(() => prisma.$disconnect())
  .catch((err) => {
    console.error(err);
    prisma.$disconnect();
    process.exit(1);
  });
