"use client";

import { Card } from "@/components/ui";
import { SimBadge } from "@/components/mark";
import { DEMO_OPPS, OpportunityTable, RouterAB } from "@/components/viz";
import { useMemo, useState } from "react";

const KINDS = ["ALL", "CEX", "DEX"] as const;
const STATUS = ["ALL", "WATCH", "ROUTED", "SKIPPED"] as const;
const LIQ = ["ALL", "Deep", "Mid", "Thin"] as const;

export default function IntelligencePage() {
  const [kind, setKind] = useState<(typeof KINDS)[number]>("ALL");
  const [status, setStatus] = useState<(typeof STATUS)[number]>("ALL");
  const [liq, setLiq] = useState<(typeof LIQ)[number]>("ALL");
  const [q, setQ] = useState("");
  const rows = useMemo(() => {
    return DEMO_OPPS.filter((r) => {
      if (kind === "CEX" && !(r.src.startsWith("CEX") || r.dst.startsWith("CEX"))) return false;
      if (kind === "DEX" && !(r.src.startsWith("DEX") || r.dst.startsWith("DEX"))) return false;
      if (status !== "ALL" && r.status !== status) return false;
      if (liq !== "ALL" && r.liq !== liq) return false;
      if (q && !`${r.pair} ${r.src} ${r.dst}`.toLowerCase().includes(q.toLowerCase())) return false;
      return true;
    });
  }, [kind, status, liq, q]);
  return (
    <div className="space-y-6">
      <div className="flex flex-wrap items-end justify-between gap-3">
        <div>
          <p className="kicker">Intelligence</p>
          <h1 className="mt-2 font-display text-3xl font-semibold">Market intelligence map</h1>
          <p className="mt-2 max-w-xl text-sm text-graphite-400">
            Operational view of venues, pairs and routes. This surface is not a conventional trading terminal.
          </p>
        </div>
        <SimBadge>Not connected to live venue APIs</SimBadge>
      </div>
      <div className="flex flex-wrap gap-2">
        {KINDS.map((c) => (
          <FilterChip key={c} active={kind === c} onClick={() => setKind(c)} label={c} />
        ))}
        {STATUS.map((c) => (
          <FilterChip key={c} active={status === c} onClick={() => setStatus(c)} label={`Status ${c}`} />
        ))}
        {LIQ.map((c) => (
          <FilterChip key={c} active={liq === c} onClick={() => setLiq(c)} label={`Liq ${c}`} />
        ))}
        <input
          value={q}
          onChange={(e) => setQ(e.target.value)}
          placeholder="Filter asset / venue"
          className="rounded-sm border border-white/10 bg-graphite-900 px-3 py-1.5 font-mono text-[12px]"
        />
      </div>
      <Card className="p-0">
        <svg viewBox="0 0 640 220" className="h-56 w-full">
          {rows.map((r, i) => {
            const y = 40 + i * 42;
            return (
              <g key={r.pair}>
                <rect x="24" y={y} width="120" height="28" fill="none" stroke="rgba(248,250,254,0.2)" />
                <text x="36" y={y + 18} fill="#F8FAFE" fontSize="11" fontFamily="ui-monospace">
                  {r.src}
                </text>
                <line x1="144" y1={y + 14} x2="280" y2={y + 14} stroke="#347BEC" />
                <rect x="280" y={y} width="80" height="28" fill="none" stroke="#347BEC" />
                <text x="292" y={y + 18} fill="#347BEC" fontSize="10" fontFamily="ui-monospace">
                  ROUTER
                </text>
                <line x1="360" y1={y + 14} x2="496" y2={y + 14} stroke="#347BEC" />
                <rect x="496" y={y} width="120" height="28" fill="none" stroke="rgba(248,250,254,0.2)" />
                <text x="508" y={y + 18} fill="#F8FAFE" fontSize="11" fontFamily="ui-monospace">
                  {r.dst}
                </text>
              </g>
            );
          })}
        </svg>
      </Card>
      <OpportunityTable rows={rows} />
      <div>
        <h2 className="font-display text-xl">Route anatomy</h2>
        <p className="mt-1 text-sm text-graphite-500">VENUE A → EXECUTION ROUTER → VENUE B</p>
        <div className="mt-4">
          <RouterAB />
        </div>
      </div>
    </div>
  );
}

function FilterChip({ label, active, onClick }: { label: string; active: boolean; onClick: () => void }) {
  return (
    <button
      type="button"
      onClick={onClick}
      className={`rounded-sm border px-3 py-1.5 text-[12px] ${active ? "border-ember text-ember" : "border-white/10 text-graphite-400"}`}
    >
      {label}
    </button>
  );
}
