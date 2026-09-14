"use client";

import { Card, Gauge } from "@/components/ui";
import { trpc } from "@/trpc/client";
import { usd } from "@/lib/utils";

export default function RanksBoPage() {
  const q = trpc.user.ranks.useQuery();
  if (!q.data) return <p className="text-slate-500">Loading…</p>;
  const { current, next, psvMeterCents, tvMeterCents } = q.data;
  return (
    <div className="space-y-6">
      <p className="kicker">Rewards</p>
      <h1 className="mt-2 font-display text-3xl font-semibold">Ranks</h1>
      <Card>
        <div className="text-sm">Current {current.name} · salary {q.data.salaryActive ? "ON" : "OFF"}</div>
        {q.data.rankHeldUntil ? <p className="text-xs text-amber-300">Held until {new Date(q.data.rankHeldUntil).toDateString()}</p> : null}
        {next ? (
          <div className="mt-4 space-y-3">
            <Gauge label={`PSV to ${next.name}`} value={Number(psvMeterCents)} max={next.psvCents} />
            <Gauge label={`TV to ${next.name}`} value={Number(tvMeterCents)} max={next.rankUpTvCents} />
            <p className="text-xs text-slate-500">Spillover remainder stays on the meter after a rank-up consumes quota.</p>
          </div>
        ) : (
          <p className="mt-3 text-sm">Legacy — top rank.</p>
        )}
      </Card>
      <Card>
        <h2 className="text-xs uppercase tracking-widest text-slate-500">Bonuses paid</h2>
        {(q.data.paid ?? []).map((b) => (
          <div key={b.id} className="mt-2 font-mono text-xs">
            {b.rank} {usd(b.amountCents)}
          </div>
        ))}
      </Card>
    </div>
  );
}
