"use client";

import { Card } from "@/components/ui";
import { SimBadge } from "@/components/mark";
import { Disclosure } from "@/components/disclosure";
import { trpc } from "@/trpc/client";
import { usd } from "@/lib/utils";
import { useMemo } from "react";

export default function AnalyticsPage() {
  const q = trpc.user.analytics.useQuery();
  const ov = trpc.user.overview.useQuery();
  const credits = q.data?.credits ?? [];
  const maxCredit = useMemo(() => {
    let m = 1;
    for (const c of credits) {
      const n = Number(c.amountCents);
      if (n > m) m = n;
    }
    return m;
  }, [credits]);
  const bpsBuckets = useMemo(() => {
    const map = new Map<number, number>();
    for (const c of credits) {
      map.set(c.appliedBps, (map.get(c.appliedBps) ?? 0) + 1);
    }
    return [...map.entries()].sort((a, b) => a[0] - b[0]);
  }, [credits]);
  if (!q.data) return <p className="text-graphite-500">Loading analytics…</p>;
  return (
    <div className="space-y-8">
      <div className="flex flex-wrap items-end justify-between gap-3">
        <div>
          <p className="kicker">Analytics</p>
          <h1 className="mt-2 font-display text-3xl font-semibold">Execution quality & capital</h1>
          <p className="mt-2 max-w-xl text-sm text-graphite-400">
            Performance, fees and credits below are ledger-backed. Venue heatmaps without a connected adapter are marked
            simulation.
          </p>
        </div>
        <SimBadge>Venue / slippage heatmaps not connected</SimBadge>
      </div>

      <div className="grid gap-4 md:grid-cols-2">
        <Card>
          <div className="font-mono text-[10px] uppercase tracking-ledger text-graphite-500">DIRECT_DEPOSIT funded</div>
          <div className="mt-2 font-display text-2xl text-ember">{usd(q.data.dd)}</div>
        </Card>
        <Card>
          <div className="font-mono text-[10px] uppercase tracking-ledger text-graphite-500">Wallet funded (no volume)</div>
          <div className="mt-2 font-display text-2xl">{usd(q.data.wallet)}</div>
        </Card>
      </div>

      <Card>
        <h2 className="font-display text-lg">Credit heatmap (posted)</h2>
        <p className="mt-1 text-xs text-graphite-500">Intensity = credited amount vs max in window. Empty cells = no post.</p>
        <div className="mt-4 grid grid-cols-7 gap-1 sm:grid-cols-10">
          {credits.length === 0 ? (
            <p className="col-span-full text-sm text-graphite-500">No daily credits posted yet.</p>
          ) : (
            credits.map((c) => {
              const pct = Number(c.amountCents) / maxCredit;
              return (
                <div
                  key={c.id}
                  title={`${c.businessDate} ${usd(c.amountCents)} @ ${c.appliedBps} bps`}
                  className="h-8"
                  style={{ background: `rgba(255,90,54,${0.12 + pct * 0.78})` }}
                />
              );
            })
          )}
        </div>
      </Card>

      <div className="grid gap-4 lg:grid-cols-2">
        <Card>
          <h2 className="font-display text-lg">Applied bps distribution</h2>
          <div className="mt-4 space-y-2">
            {bpsBuckets.length === 0 ? (
              <p className="text-sm text-graphite-500">No distribution until credits post.</p>
            ) : (
              bpsBuckets.map(([bps, n]) => (
                <div key={bps} className="flex items-center gap-3 font-mono text-[12px]">
                  <span className="w-20 text-graphite-500">{bps} bps</span>
                  <div className="h-2 flex-1 bg-white/10">
                    <div className="h-full bg-ember" style={{ width: `${Math.min(100, n * 12)}%` }} />
                  </div>
                  <span>{n}</span>
                </div>
              ))
            )}
          </div>
        </Card>
        <Card>
          <h2 className="font-display text-lg">Venue analysis</h2>
          <p className="mt-1 text-[11px] text-graphite-500">Simulation — adapters not connected.</p>
          <div className="mt-4 space-y-2 font-mono text-[12px]">
            {[
              ["CEX-A", "Fill quality n/a"],
              ["DEX-B", "Gas / slip n/a"],
              ["CEX-F", "Latency n/a"],
            ].map(([v, n]) => (
              <div key={v} className="flex justify-between border-t border-white/[0.06] py-2">
                <span>{v}</span>
                <span className="text-graphite-500">{n}</span>
              </div>
            ))}
          </div>
        </Card>
      </div>

      <Card>
        <h2 className="font-display text-lg">Daily credits vs cap (posted)</h2>
        <div className="mt-3 space-y-1 font-mono text-xs">
          {credits.map((c) => (
            <div key={c.id} className="flex justify-between border-t border-white/[0.06] py-1">
              <span className="text-graphite-500">{c.businessDate}</span>
              <span>
                {usd(c.amountCents)} @ {c.appliedBps} bps (engine {c.engineBps} / cap {c.licenseCapBps})
              </span>
            </div>
          ))}
        </div>
      </Card>
      {ov.data ? <Disclosure>{ov.data.copy.dailyCapDisclaimer}</Disclosure> : null}
    </div>
  );
}
