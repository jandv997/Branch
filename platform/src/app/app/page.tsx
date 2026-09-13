"use client";

import { Button, Card, Gauge, StatCard } from "@/components/ui";
import { trpc } from "@/trpc/client";
import { usd } from "@/lib/utils";
import { QrImg } from "@/components/qr";

export default function OverviewPage() {
  const q = trpc.user.overview.useQuery();
  if (q.isError) return <Card className="text-red-300">{q.error.message}</Card>;
  if (!q.data) return <p className="text-slate-500">Loading overview…</p>;
  const d = q.data;
  const next = d.nextRank;
  return (
    <div className="space-y-6">
      <div>
        <h1 className="text-2xl">Overview</h1>
        <p className="text-xs text-slate-500">{d.copy.dailyCapDisclaimer}</p>
      </div>
      <div className="grid gap-4 md:grid-cols-4">
        <StatCard label="License" value={d.user.license?.tier ?? "NONE"} hint={d.user.licenseStatus} />
        <StatCard label="Rank" value={d.user.rank} hint={d.user.salaryActive ? "Salary on" : "Salary off"} />
        <StatCard
          label="Today credit vs cap"
          value={`${usd(d.creditToday)} / ${usd(d.capToday)}`}
          hint="Up to license cap · not guaranteed"
        />
        <StatCard label="FS window" value={d.fsDaysLeft != null ? `${d.fsDaysLeft}d left` : "Closed"} />
      </div>
      <div className="grid gap-4 md:grid-cols-2">
        <Card>
          <h2 className="text-sm uppercase tracking-widest text-slate-400">Wallets</h2>
          <div className="mt-3 space-y-2 font-mono text-sm">
            {d.wallets.map((w) => (
              <div key={w.id} className="flex justify-between">
                <span className="text-slate-500">{w.kind}</span>
                <span className="text-cyan">{usd(w.balanceCents)}</span>
              </div>
            ))}
          </div>
        </Card>
        <Card>
          <h2 className="text-sm uppercase tracking-widest text-slate-400">Next rank meters</h2>
          <div className="mt-4 space-y-4">
            <Gauge label="PSV toward next" value={Number(d.psvMeterCents)} max={next?.psvCents ?? 1} />
            <Gauge label="TV toward next" value={Number(d.tvMeterCents)} max={next?.rankUpTvCents ?? 1} />
            <p className="text-xs text-slate-500">
              Monthly keep TV {usd(d.keepTvMonthCents)} {d.keepTvMonthKey ? `(${d.keepTvMonthKey})` : ""} — separate from rank-up meters.
            </p>
          </div>
        </Card>
      </div>
      <Card className="flex flex-wrap items-center gap-6">
        <div>
          <div className="text-[10px] uppercase tracking-widest text-slate-500">Referral link</div>
          <div className="mt-1 font-mono text-xs text-cyan">{d.referralUrl}</div>
        </div>
        <QrImg value={d.referralUrl} />
      </Card>
      {d.user.licenseStatus !== "ACTIVE" ? (
        <LicenseBuy />
      ) : null}
    </div>
  );
}

function LicenseBuy() {
  const utils = trpc.useUtils();
  const buy = trpc.user.licensePurchase.useMutation({ onSuccess: () => utils.user.overview.invalidate() });
  return (
    <Card>
      <h2 className="text-sm">Activate a license (dev adapter simulates payment)</h2>
      <div className="mt-3 flex gap-2">
        {(["PULSE", "CORE", "APEX", "PRIME"] as const).map((t) => (
          <Button key={t} variant="outline" onClick={() => buy.mutate({ tier: t })}>
            {t}
          </Button>
        ))}
      </div>
    </Card>
  );
}
