"use client";

import { Card, Gauge } from "@/components/ui";
import { trpc } from "@/trpc/client";
import { usd } from "@/lib/utils";

export default function FastStartPage() {
  const q = trpc.user.fastStart.useQuery();
  if (!q.data) return <p className="text-slate-500">Loading…</p>;
  const p = q.data.preview;
  return (
    <div className="space-y-6">
      <h1 className="text-2xl">Fast Start · day {q.data.day}/{q.data.windowDays}</h1>
      <p className="text-sm text-slate-400">{q.data.copy}</p>
      <Card>
        <p className="text-xs uppercase tracking-widest text-slate-500">Clip preview on a $1,000 sample DD</p>
        <div className="mt-3 font-mono text-sm">
          volume {p.volumePath ?? "—"} · headcount {p.headcountPath ?? "—"} · raw {p.rawBps} bps · clipped {p.clippedBps} bps · {usd(p.amountCents)}
        </div>
        <p className="mt-2 text-xs text-slate-500">{p.reason} {p.clipReason ?? ""}</p>
        <div className="mt-4 space-y-3">
          <Gauge label="Volume path" value={p.volumePath ? 1 : 0} max={1} />
          <Gauge label="Headcount path" value={p.headcountPath ? 1 : 0} max={1} />
        </div>
      </Card>
      {(q.data.pays ?? []).map((row) => (
        <Card key={row.id} className="font-mono text-xs">
          {usd(row.amountCents)} · vol {row.volumePath} · hc {row.headcountPath} · clip {row.clipReason ?? "none"}
        </Card>
      ))}
    </div>
  );
}
