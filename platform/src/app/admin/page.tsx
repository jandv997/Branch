"use client";

import { Card, StatCard } from "@/components/ui";
import { ActivityFeed } from "@/components/activity-feed";
import { SimBadge } from "@/components/mark";
import { trpc } from "@/trpc/client";
import { usd } from "@/lib/utils";

export default function AdminHome() {
  const a = trpc.admin.analytics.useQuery();
  const halt = trpc.admin.haltGet.useQuery();
  if (!a.data) return <p className="text-graphite-500">Loading command…</p>;
  const users = a.data.ranks.reduce((n, r) => n + r._count, 0);
  const arr = BigInt(a.data.mrrCents) * 12n;
  const liab = (a.data.liabilities ?? []).reduce((n, l) => n + BigInt(l._sum.balanceCents ?? 0), 0n);
  return (
    <div className="space-y-8">
      <div className="flex flex-wrap items-end justify-between gap-3">
        <div>
          <p className="kicker">Operations</p>
          <h1 className="mt-2 font-display text-3xl font-semibold">Command center</h1>
          <p className="mt-2 max-w-xl text-sm text-graphite-400">
            Ledger-backed licenses, commissions and capital. Venue health tiles are labeled until adapters are live.
          </p>
        </div>
        <SimBadge>Exchange / API health not connected</SimBadge>
      </div>

      <div className="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
        <StatCard label="Active users (by rank)" value={String(users)} />
        <StatCard label="Active licenses" value={String(a.data.activeLicenses)} />
        <StatCard label="MRR (license/12)" value={usd(a.data.mrrCents)} hint="From posted license table" />
        <StatCard label="ARR (12 × MRR)" value={usd(arr)} hint="Derived, not a forecast" />
        <StatCard label="DIRECT_DEPOSIT" value={usd(a.data.dd)} />
        <StatCard label="Wallet funding" value={usd(a.data.wallets)} hint="No PSV/TV" />
        <StatCard label="Tree+FS posted" value={usd(a.data.treeAndFs)} />
        <StatCard label="Wallet liability" value={usd(liab)} />
      </div>

      <div className="grid gap-4 lg:grid-cols-2">
        <Card>
          <h2 className="font-mono text-[11px] uppercase tracking-ledger text-graphite-500">System halt</h2>
          <dl className="mt-3 grid grid-cols-2 gap-3 font-mono text-sm">
            <div>
              <div className="text-[10px] uppercase tracking-ledger text-graphite-500">Credits</div>
              <div className={halt.data?.haltCredits ? "text-red-300" : "text-ember"}>
                {halt.data?.haltCredits ? "HALTED" : "RUNNING"}
              </div>
            </div>
            <div>
              <div className="text-[10px] uppercase tracking-ledger text-graphite-500">Withdrawals</div>
              <div className={halt.data?.haltWithdraws ? "text-red-300" : "text-ember"}>
                {halt.data?.haltWithdraws ? "HALTED" : "RUNNING"}
              </div>
            </div>
          </dl>
        </Card>
        <Card>
          <h2 className="font-mono text-[11px] uppercase tracking-ledger text-graphite-500">Connectivity</h2>
          <div className="mt-3 space-y-2 font-mono text-[12px]">
            {[
              ["CEX adapters", "NOT CONNECTED"],
              ["DEX adapters", "NOT CONNECTED"],
              ["RPC / pools", "NOT CONNECTED"],
              ["Payment adapter", "DEV / SIMULATED"],
            ].map(([k, v]) => (
              <div key={k} className="flex justify-between border-t border-white/[0.06] py-1">
                <span className="text-graphite-400">{k}</span>
                <span className="text-ember">{v}</span>
              </div>
            ))}
          </div>
        </Card>
      </div>

      <Card>
        <h2 className="font-mono text-[11px] uppercase tracking-ledger text-graphite-500">Rank pyramid</h2>
        <div className="mt-3 space-y-1 font-mono text-xs">
          {a.data.ranks.map((r) => (
            <div key={r.rank} className="flex items-center gap-3">
              <span className="w-40 text-graphite-400">{r.rank}</span>
              <div className="h-2 flex-1 bg-white/10">
                <div className="h-full bg-ember" style={{ width: `${Math.min(100, r._count * 8)}%` }} />
              </div>
              <span className="text-ember">{r._count}</span>
            </div>
          ))}
        </div>
      </Card>

      <Card>
        <h2 className="font-mono text-[11px] uppercase tracking-ledger text-graphite-500">Wallet liabilities</h2>
        <div className="mt-3 space-y-1 font-mono text-xs">
          {(a.data.liabilities ?? []).map((l) => (
            <div key={l.kind} className="flex justify-between">
              <span className="text-graphite-400">{l.kind}</span>
              <span>{usd(l._sum.balanceCents ?? 0)}</span>
            </div>
          ))}
        </div>
      </Card>

      <Card>
        <div className="flex items-center justify-between">
          <div className="font-mono text-[11px] uppercase tracking-ledger text-graphite-500">Latest updates</div>
          <a href="/admin/updates" className="text-[10px] uppercase tracking-ledger text-ember">
            Full feed →
          </a>
        </div>
        <div className="mt-4">
          <AdminOverviewActivity />
        </div>
      </Card>
    </div>
  );
}

function AdminOverviewActivity() {
  const q = trpc.admin.activity.useQuery({ take: 8 });
  if (q.isLoading) return <p className="text-xs text-graphite-500">Loading activity…</p>;
  if (q.isError) return <p className="text-xs text-red-300">{q.error.message}</p>;
  return <ActivityFeed items={q.data ?? []} empty="No operational events yet." />;
}
