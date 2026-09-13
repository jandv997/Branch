"use client";

import { Card } from "@/components/ui";
import { trpc } from "@/trpc/client";
import { usd } from "@/lib/utils";

export default function AnalyticsPage() {
  const q = trpc.user.analytics.useQuery();
  if (!q.data) return <p className="text-slate-500">Loading…</p>;
  return (
    <div className="space-y-6">
      <h1 className="text-2xl">Analytics</h1>
      <div className="grid gap-4 md:grid-cols-2">
        <Card>
          <div className="text-[10px] uppercase tracking-widest text-slate-500">DIRECT_DEPOSIT funded</div>
          <div className="font-mono text-2xl text-cyan">{usd(q.data.dd)}</div>
        </Card>
        <Card>
          <div className="text-[10px] uppercase tracking-widest text-slate-500">Wallet funded (no volume)</div>
          <div className="font-mono text-2xl">{usd(q.data.wallet)}</div>
        </Card>
      </div>
      <Card>
        <h2 className="text-xs uppercase tracking-widest text-slate-500">Daily credits vs cap (posted)</h2>
        <div className="mt-3 space-y-1 font-mono text-xs">
          {q.data.credits.map((c) => (
            <div key={c.id} className="flex justify-between">
              <span className="text-slate-500">{c.businessDate}</span>
              <span>
                {usd(c.amountCents)} @ {c.appliedBps} bps (engine {c.engineBps} / cap {c.licenseCapBps})
              </span>
            </div>
          ))}
        </div>
      </Card>
    </div>
  );
}
