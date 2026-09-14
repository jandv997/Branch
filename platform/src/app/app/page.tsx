"use client";

import { Button, Card, Gauge, StatCard } from "@/components/ui";
import { ActivityFeed } from "@/components/activity-feed";
import { trpc } from "@/trpc/client";
import { usd } from "@/lib/utils";
import { SimBadge } from "@/components/mark";
import Link from "next/link";

const SIM_STATUS = [
  ["Markets monitored", "47"],
  ["Liquidity venues", "32"],
  ["Opportunities analyzed", "18,492"],
  ["Execution routes evaluated", "7,318"],
  ["Active strategies", "24"],
] as const;

export default function OverviewPage() {
  const q = trpc.user.overview.useQuery();
  if (q.isError) return <Card className="text-red-300">{q.error.message}</Card>;
  if (!q.data) return <p className="text-graphite-500">Loading intelligence center…</p>;
  const d = q.data;
  const next = d.nextRank;
  const live = d.user.licenseStatus === "ACTIVE";
  return (
    <div className="space-y-8">
      <div className="flex flex-wrap items-end justify-between gap-4">
        <div>
          <p className="kicker">Intelligence center</p>
          <h1 className="mt-2 font-display text-3xl font-semibold">System status</h1>
          <p className="mt-2 max-w-xl text-xs text-graphite-500">{d.copy.dailyCapDisclaimer}</p>
        </div>
        <div className="flex items-center gap-3">
          <span className="font-mono text-[11px] uppercase tracking-ledger text-graphite-500">Engine</span>
          <span className={live ? "font-mono text-sm text-ember" : "font-mono text-sm text-graphite-400"}>
            {live ? "ACTIVE" : d.user.licenseStatus}
          </span>
        </div>
      </div>

      <div className="grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
        {SIM_STATUS.map(([label, value]) => (
          <div key={label} className="glass p-4">
            <div className="font-mono text-[10px] uppercase tracking-ledger text-graphite-500">{label}</div>
            <div className="mt-2 font-display text-2xl">{value}</div>
          </div>
        ))}
      </div>
      <SimBadge>Illustrative UI values only — not live venue telemetry</SimBadge>

      <div className="grid gap-4 md:grid-cols-4">
        <StatCard label="License" value={d.user.license?.tier ?? "NONE"} hint={d.user.licenseStatus} />
        <StatCard label="Rank" value={d.user.rank} hint={d.user.salaryActive ? "Salary on" : "Salary off"} />
        <StatCard
          label="Today credit vs cap"
          value={`${usd(d.creditToday)} / ${usd(d.capToday)}`}
          hint="Up to license cap · ledger-backed"
        />
        <StatCard label="FS window" value={d.fsDaysLeft != null ? `${d.fsDaysLeft}d left` : "Closed"} />
      </div>

      <div className="grid gap-4 md:grid-cols-2">
        <Card>
          <h2 className="font-mono text-[11px] uppercase tracking-ledger text-graphite-500">Capital (ledger)</h2>
          <div className="mt-3 space-y-2 font-mono text-sm">
            {d.wallets.map((w) => (
              <div key={w.id} className="flex justify-between">
                <span className="text-graphite-500">{w.kind}</span>
                <span className="text-ember">{usd(w.balanceCents)}</span>
              </div>
            ))}
          </div>
        </Card>
        <Card>
          <h2 className="font-mono text-[11px] uppercase tracking-ledger text-graphite-500">Rank meters (PSV / TV)</h2>
          <div className="mt-4 space-y-4">
            <Gauge label="PSV toward next" value={Number(d.psvMeterCents)} max={next?.psvCents ?? 1} />
            <Gauge label="TV toward next" value={Number(d.tvMeterCents)} max={next?.rankUpTvCents ?? 1} />
          </div>
        </Card>
      </div>

      <div className="flex flex-wrap gap-3">
        <Link href="/app/intelligence" className="rounded-sm bg-ember px-4 py-2 text-sm font-medium text-white">
          Open market map
        </Link>
        <Link href="/app/portfolios" className="rounded-sm border border-white/10 px-4 py-2 text-sm">
          Portfolio command
        </Link>
        <Link href="/app/licenses" className="rounded-sm border border-white/10 px-4 py-2 text-sm">
          License center
        </Link>
      </div>

      <Card>
        <div className="flex items-center justify-between">
          <h2 className="font-mono text-[11px] uppercase tracking-ledger text-graphite-500">Ledger events</h2>
          <a href="/app/updates" className="text-[11px] text-ember">
            Full timeline →
          </a>
        </div>
        <div className="mt-4">
          <OverviewActivity />
        </div>
      </Card>
      {d.user.licenseStatus !== "ACTIVE" ? <LicenseBuy /> : null}
    </div>
  );
}

function OverviewActivity() {
  const q = trpc.user.activity.useQuery({ take: 8 });
  if (q.isLoading) return <p className="text-xs text-graphite-500">Loading activity…</p>;
  if (q.isError) return <p className="text-xs text-red-300">{q.error.message}</p>;
  return <ActivityFeed items={q.data ?? []} empty="No account activity yet." />;
}

function LicenseBuy() {
  const utils = trpc.useUtils();
  const buy = trpc.user.licensePurchase.useMutation({ onSuccess: () => utils.user.overview.invalidate() });
  return (
    <Card>
      <h2 className="text-sm">Activate a license (dev adapter simulates payment)</h2>
      <div className="mt-3 flex flex-wrap gap-2">
        {(["PULSE", "CORE", "APEX", "PRIME"] as const).map((t) => (
          <Button key={t} variant="outline" onClick={() => buy.mutate({ tier: t })}>
            {t}
          </Button>
        ))}
      </div>
    </Card>
  );
}
