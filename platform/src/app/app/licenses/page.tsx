"use client";

import { Button, Card } from "@/components/ui";
import { Disclosure } from "@/components/disclosure";
import { trpc } from "@/trpc/client";
import { bpsLabel, usd } from "@/lib/utils";
import type { LicenseTier } from "@/domain/comp/config";

export default function LicenseCenterPage() {
  const ov = trpc.user.overview.useQuery();
  const cfg = trpc.public.config.useQuery();
  const utils = trpc.useUtils();
  const buy = trpc.user.licensePurchase.useMutation({ onSuccess: () => utils.user.overview.invalidate() });
  if (!ov.data || !cfg.data) return <p className="text-graphite-500">Loading license center…</p>;
  const lic = ov.data.user.license;
  const start = ov.data.user.activatedAt ? new Date(ov.data.user.activatedAt).toISOString().slice(0, 10) : "—";
  const expiry = ov.data.user.licenseRenewsAt ? new Date(ov.data.user.licenseRenewsAt).toISOString().slice(0, 10) : "—";
  return (
    <div className="space-y-6">
      <div>
        <p className="kicker">Licenses</p>
        <h1 className="mt-2 font-display text-3xl font-semibold">Software subscription</h1>
        <p className="mt-2 max-w-xl text-sm text-graphite-400">
          12-month software license. Monthly payment options. Tiers come from admin-configurable compensation config.
        </p>
      </div>
      <Card>
        <div className="grid gap-4 sm:grid-cols-2 md:grid-cols-4">
          <div>
            <div className="font-mono text-[10px] uppercase tracking-ledger text-graphite-500">Current tier</div>
            <div className="mt-1 font-display text-2xl">{lic?.tier ?? "NONE"}</div>
          </div>
          <div>
            <div className="font-mono text-[10px] uppercase tracking-ledger text-graphite-500">Payment status</div>
            <div className="mt-1 text-lg">{ov.data.user.licenseStatus}</div>
          </div>
          <div>
            <div className="font-mono text-[10px] uppercase tracking-ledger text-graphite-500">Activated</div>
            <div className="mt-1 font-mono text-lg">{start}</div>
          </div>
          <div>
            <div className="font-mono text-[10px] uppercase tracking-ledger text-graphite-500">Renews / expiry</div>
            <div className="mt-1 font-mono text-lg">{expiry}</div>
          </div>
          <div>
            <div className="font-mono text-[10px] uppercase tracking-ledger text-graphite-500">Monthly payment</div>
            <div className="mt-1 text-lg">{lic ? usd(lic.priceCents) : "—"}</div>
          </div>
          <div>
            <div className="font-mono text-[10px] uppercase tracking-ledger text-graphite-500">Daily cap</div>
            <div className="mt-1 text-lg">{lic ? bpsLabel(lic.dailyCapBps) : "—"}</div>
          </div>
          <div>
            <div className="font-mono text-[10px] uppercase tracking-ledger text-graphite-500">Usage today</div>
            <div className="mt-1 text-lg">
              {usd(ov.data.creditToday)} / {usd(ov.data.capToday)}
            </div>
          </div>
          <div>
            <div className="font-mono text-[10px] uppercase tracking-ledger text-graphite-500">Upgrade</div>
            <div className="mt-1 text-lg">Select a higher tier below</div>
          </div>
        </div>
        <p className="mt-4 text-xs text-graphite-500">{ov.data.copy.dailyCapDisclaimer}</p>
      </Card>
      <div className="grid gap-px bg-white/[0.08] md:grid-cols-2">
        {cfg.data.licenses.map((l) => (
          <div key={l.tier} className="bg-graphite-950 p-5">
            <div className="flex items-center justify-between">
              <h2 className="font-display text-xl">{l.name}</h2>
              {lic?.tier === l.tier ? <span className="font-mono text-[10px] text-ember">CURRENT</span> : null}
            </div>
            <div className="mt-2 font-mono text-2xl">
              {usd(l.priceCents)} <span className="text-sm text-graphite-500">/mo</span>
            </div>
            <ul className="mt-3 space-y-1 text-sm text-graphite-400">
              <li>Cap {usd(l.capCents)}</li>
              <li>Credit {bpsLabel(l.dailyCapBps)}</li>
              <li>Min fund {usd(l.minFundCents)}</li>
            </ul>
            <Button className="mt-4" variant="outline" onClick={() => buy.mutate({ tier: l.tier as LicenseTier })}>
              {lic?.tier === l.tier ? "Renew (dev adapter)" : `Select ${l.name}`}
            </Button>
          </div>
        ))}
      </div>
      <Disclosure>
        License fees buy software access. They are not an investment product. Dev payment adapter simulates settlement.
      </Disclosure>
    </div>
  );
}
