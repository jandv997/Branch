"use client";

import { Card, Gauge } from "@/components/ui";
import { trpc } from "@/trpc/client";
import { usd } from "@/lib/utils";
import Link from "next/link";

export default function RewardsPage() {
  const ov = trpc.user.overview.useQuery();
  const ranks = trpc.user.ranks.useQuery();
  if (!ov.data) return <p className="text-graphite-500">Loading rewards…</p>;
  const next = ov.data.nextRank;
  return (
    <div className="space-y-6">
      <div>
        <p className="kicker">Rewards</p>
        <h1 className="mt-2 font-display text-3xl font-semibold">Leadership & commissions</h1>
        <p className="mt-2 max-w-xl text-sm text-graphite-400">
          Rank and commission figures are ledger-backed. Rules live in the admin-configurable compensation engine — the
          UI does not invent payouts.
        </p>
      </div>
      <div className="grid gap-4 md:grid-cols-3">
        <Card>
          <div className="font-mono text-[10px] uppercase tracking-ledger text-graphite-500">Rank</div>
          <div className="mt-1 font-display text-2xl">{ov.data.user.rank}</div>
        </Card>
        <Card>
          <Gauge label="PSV" value={Number(ov.data.psvMeterCents)} max={next?.psvCents ?? 1} />
        </Card>
        <Card>
          <Gauge label="TV" value={Number(ov.data.tvMeterCents)} max={next?.rankUpTvCents ?? 1} />
        </Card>
      </div>
      <Card>
        <h2 className="font-display text-lg">Wallets</h2>
        <div className="mt-3 space-y-2 font-mono text-sm">
          {ov.data.wallets.map((w) => (
            <div key={w.id} className="flex justify-between">
              <span className="text-graphite-500">{w.kind}</span>
              <span>{usd(w.balanceCents)}</span>
            </div>
          ))}
        </div>
      </Card>
      {ranks.data ? (
        <p className="text-xs text-graphite-500">{ranks.data.table.length} rank definitions from config.</p>
      ) : null}
      <div className="flex gap-4 text-sm">
        <Link href="/app/ranks" className="text-ember">
          Rank table →
        </Link>
        <Link href="/app/referrals" className="text-ember">
          Referral ledger →
        </Link>
        <Link href="/compensation" className="text-ember">
          Public rules →
        </Link>
      </div>
    </div>
  );
}
