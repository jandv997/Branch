"use client";

import { Card } from "@/components/ui";
import { trpc } from "@/trpc/client";
import { usd } from "@/lib/utils";

export default function AdminAnalytics() {
  const a = trpc.admin.analytics.useQuery();
  if (!a.data) return <p className="text-slate-500">Loading…</p>;
  return (
    <div className="space-y-4">
      <h1 className="text-2xl">Analytics</h1>
      <Card className="grid gap-3 md:grid-cols-3 font-mono text-sm">
        <div>MRR {usd(a.data.mrrCents)}</div>
        <div>DD {usd(a.data.dd)}</div>
        <div>Wallets {usd(a.data.wallets)}</div>
        <div>Tree+FS {usd(a.data.treeAndFs)}</div>
        <div>Active licenses {a.data.activeLicenses}</div>
        <div>FS pays {a.data.fs._count}</div>
      </Card>
      <Card>
        <h2 className="text-xs uppercase tracking-widest text-slate-500">Wallet liabilities</h2>
        {(a.data.liabilities ?? []).map((l) => (
          <div key={l.kind} className="flex justify-between font-mono text-xs">
            <span>{l.kind}</span>
            <span className="text-cyan">{usd(l._sum.balanceCents ?? 0)}</span>
          </div>
        ))}
      </Card>
    </div>
  );
}
