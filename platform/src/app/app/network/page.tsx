"use client";

import { Card } from "@/components/ui";
import { trpc } from "@/trpc/client";
import { usd } from "@/lib/utils";
import { QrImg } from "@/components/qr";
import Link from "next/link";
import { useMemo, useState } from "react";

export default function NetworkPage() {
  const team = trpc.user.team.useQuery();
  const ov = trpc.user.overview.useQuery();
  const refs = trpc.user.referrals.useQuery();
  const [level, setLevel] = useState(0);
  const nodesAll = useMemo(
    () => (team.data ? team.data.levels.flatMap((l) => l.users.map((u) => ({ ...u, level: l.level }))) : []),
    [team.data],
  );
  const nodes = level === 0 ? nodesAll : nodesAll.filter((n) => n.level === level);
  if (!team.data || !ov.data) return <p className="text-graphite-500">Loading partner network…</p>;
  return (
    <div className="space-y-6">
      <div>
        <p className="kicker">Partner network</p>
        <h1 className="mt-2 font-display text-3xl font-semibold">Organization topology</h1>
        <p className="mt-2 max-w-2xl text-sm text-graphite-400">
          Professional partner view. Volume is PSV/TV from DIRECT_DEPOSIT only — not a “get rich” downline. A license
          holder can operate with zero team.
        </p>
      </div>
      <div className="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
        <Card>
          <div className="font-mono text-[10px] uppercase tracking-ledger text-graphite-500">Personal customers (L1)</div>
          <div className="mt-2 font-display text-2xl">{team.data.fundedL1Count}</div>
        </Card>
        <Card>
          <div className="font-mono text-[10px] uppercase tracking-ledger text-graphite-500">PSV (ledger)</div>
          <div className="mt-2 font-display text-2xl">{usd(team.data.psvMeterCents)}</div>
        </Card>
        <Card>
          <div className="font-mono text-[10px] uppercase tracking-ledger text-graphite-500">TV L1–L7</div>
          <div className="mt-2 font-display text-2xl">{usd(team.data.tvMeterCents)}</div>
        </Card>
        <Card>
          <div className="font-mono text-[10px] uppercase tracking-ledger text-graphite-500">Rank</div>
          <div className="mt-2 font-display text-2xl">{ov.data.user.rank}</div>
        </Card>
      </div>
      <div className="flex flex-wrap gap-2">
        {[0, 1, 2, 3, 4, 5, 6, 7].map((n) => (
          <button
            key={n}
            type="button"
            onClick={() => setLevel(n)}
            className={`rounded-sm border px-3 py-1.5 text-[12px] ${level === n ? "border-ember text-ember" : "border-white/10 text-graphite-400"}`}
          >
            {n === 0 ? "All levels" : `L${n}`}
          </button>
        ))}
      </div>
      <Card>
        <h2 className="font-display text-lg">Network graph</h2>
        <p className="mt-1 text-xs text-graphite-500">Nodes = partners. Edges = sponsor relationship. Filter by level.</p>
        <svg viewBox="0 0 640 240" className="mt-4 h-56 w-full">
          <circle cx="320" cy="120" r="14" fill="none" stroke="#FF5A36" />
          <text x="308" y="124" fill="#FF5A36" fontSize="10">
            YOU
          </text>
          {nodes.slice(0, 12).map((n, i) => {
            const a = (i / Math.max(nodes.length, 1)) * Math.PI * 2;
            const rad = 40 + n.level * 22;
            const x = 320 + Math.cos(a) * rad;
            const y = 120 + Math.sin(a) * rad * 0.7;
            return (
              <g key={n.id}>
                <line x1="320" y1="120" x2={x} y2={y} stroke="rgba(243,239,230,0.12)" />
                <rect x={x - 4} y={y - 4} width="8" height="8" fill={n.level === 1 ? "#FF5A36" : "#F3EFE6"} />
              </g>
            );
          })}
        </svg>
      </Card>
      <Card className="flex flex-wrap items-center gap-6">
        <div>
          <div className="font-mono text-[10px] uppercase tracking-ledger text-graphite-500">Referral URL</div>
          <div className="mt-1 font-mono text-xs text-ember">{ov.data.referralUrl}</div>
        </div>
        <QrImg value={ov.data.referralUrl} />
      </Card>
      <div className="flex flex-wrap gap-4 text-sm">
        <Link href="/app/referrals" className="text-ember">
          Commission history →
        </Link>
        <Link href="/app/fast-start" className="text-ember">
          Fast Start →
        </Link>
        <Link href="/app/team" className="text-ember">
          Level tables →
        </Link>
      </div>
      {refs.data ? (
        <p className="text-xs text-graphite-500">{refs.data.length} referral ledger rows on this account.</p>
      ) : null}
    </div>
  );
}
