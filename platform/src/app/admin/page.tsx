"use client";

import { Card, StatCard } from "@/components/ui";
import { trpc } from "@/trpc/client";
import { usd } from "@/lib/utils";

export default function AdminHome() {
  const a = trpc.admin.analytics.useQuery();
  const halt = trpc.admin.haltGet.useQuery();
  if (!a.data) return <p className="text-slate-500">Loading command…</p>;
  return (
    <div className="space-y-6">
      <h1 className="text-2xl">Admin command</h1>
      <div className="grid gap-4 md:grid-cols-4">
        <StatCard label="MRR (license/12)" value={usd(a.data.mrrCents)} />
        <StatCard label="DIRECT_DEPOSIT" value={usd(a.data.dd)} />
        <StatCard label="Wallet funding" value={usd(a.data.wallets)} hint="No PSV/TV" />
        <StatCard label="Tree+FS posted" value={usd(a.data.treeAndFs)} />
      </div>
      <Card>
        <div className="text-xs uppercase tracking-widest text-slate-500">Halt</div>
        <pre className="mt-2 text-xs">{JSON.stringify(halt.data, null, 2)}</pre>
      </Card>
      <Card>
        <div className="text-xs uppercase tracking-widest text-slate-500">Rank pyramid</div>
        <div className="mt-2 space-y-1 font-mono text-xs">
          {a.data.ranks.map((r) => (
            <div key={r.rank} className="flex justify-between">
              <span>{r.rank}</span>
              <span className="text-cyan">{r._count}</span>
            </div>
          ))}
        </div>
      </Card>
    </div>
  );
}
