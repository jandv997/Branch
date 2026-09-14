"use client";

import { Button, Card, DepositSourcePicker, EmptyState, Input, Label } from "@/components/ui";
import { SimBadge } from "@/components/mark";
import { Disclosure } from "@/components/disclosure";
import { trpc } from "@/trpc/client";
import { usd } from "@/lib/utils";
import { useMemo, useState } from "react";
import type { FundingSource } from "@/domain/comp/config";

export default function PortfoliosPage() {
  const q = trpc.user.portfolios.useQuery();
  const ov = trpc.user.overview.useQuery();
  const utils = trpc.useUtils();
  const fund = trpc.user.fundPortfolio.useMutation({ onSuccess: () => utils.user.portfolios.invalidate() });
  const pause = trpc.user.pausePortfolio.useMutation({ onSuccess: () => utils.user.portfolios.invalidate() });
  const [amount, setAmount] = useState("500");
  const [source, setSource] = useState<FundingSource>("DIRECT_DEPOSIT");
  const books = q.data ?? [];
  const totals = useMemo(() => {
    const principal = books.reduce((a, p) => a + BigInt(p.principalCents), 0n);
    const cap = books.reduce((a, p) => a + BigInt(p.capCents), 0n);
    const byTier = new Map<string, bigint>();
    for (const p of books) {
      byTier.set(p.licenseTier, (byTier.get(p.licenseTier) ?? 0n) + BigInt(p.principalCents));
    }
    return { principal, cap, byTier };
  }, [books]);
  if (q.isError) return <Card className="text-red-300">{q.error.message}</Card>;
  return (
    <div className="space-y-8">
      <div className="flex flex-wrap items-end justify-between gap-3">
        <div>
          <p className="kicker">Portfolio</p>
          <h1 className="mt-2 font-display text-3xl font-semibold">Command center</h1>
          <p className="mt-2 max-w-xl text-sm text-graphite-400">
            Capital allocation, exposure and cycle status from the ledger. Venue splits below are labeled simulation
            until adapters are connected.
          </p>
        </div>
        <SimBadge>Venue / asset exposure not connected</SimBadge>
      </div>

      <div className="grid gap-3 md:grid-cols-4">
        <Card>
          <div className="font-mono text-[10px] uppercase tracking-ledger text-graphite-500">Principal</div>
          <div className="mt-2 font-display text-2xl">{usd(totals.principal)}</div>
        </Card>
        <Card>
          <div className="font-mono text-[10px] uppercase tracking-ledger text-graphite-500">Cycle caps</div>
          <div className="mt-2 font-display text-2xl">{usd(totals.cap)}</div>
        </Card>
        <Card>
          <div className="font-mono text-[10px] uppercase tracking-ledger text-graphite-500">Utilization</div>
          <div className="mt-2 font-display text-2xl">
            {totals.cap === 0n ? "—" : `${Math.round(Number((totals.principal * 100n) / totals.cap))}%`}
          </div>
        </Card>
        <Card>
          <div className="font-mono text-[10px] uppercase tracking-ledger text-graphite-500">Open books</div>
          <div className="mt-2 font-display text-2xl">{books.length}</div>
        </Card>
      </div>

      <Card>
        <h2 className="font-mono text-[11px] uppercase tracking-ledger text-graphite-500">Capital allocation (by license)</h2>
        <div className="mt-4 space-y-3">
          {totals.byTier.size === 0 ? (
            <p className="text-sm text-graphite-500">No funded principal yet.</p>
          ) : (
            [...totals.byTier.entries()].map(([tier, cents]) => {
              const pct = totals.principal === 0n ? 0 : Number((cents * 100n) / totals.principal);
              return (
                <div key={tier}>
                  <div className="mb-1 flex justify-between font-mono text-[11px] text-graphite-400">
                    <span>{tier}</span>
                    <span>
                      {usd(cents)} · {pct}%
                    </span>
                  </div>
                  <div className="h-2 bg-white/10">
                    <div className="h-full bg-ember" style={{ width: `${pct}%` }} />
                  </div>
                </div>
              );
            })
          )}
        </div>
      </Card>

      <div className="grid gap-4 lg:grid-cols-2">
        <Card>
          <h2 className="font-mono text-[11px] uppercase tracking-ledger text-graphite-500">Venue exposure</h2>
          <p className="mt-1 text-[11px] text-graphite-500">Illustrative split — not live inventory.</p>
          <div className="mt-4 grid grid-cols-2 gap-2 font-mono text-[12px]">
            {[
              ["CEX books", "42%"],
              ["DEX pools", "31%"],
              ["Stable inventory", "18%"],
              ["In-flight", "9%"],
            ].map(([k, v]) => (
              <div key={k} className="border border-white/[0.08] p-3">
                <div className="text-graphite-500">{k}</div>
                <div className="mt-1 text-ember">{v}</div>
              </div>
            ))}
          </div>
        </Card>
        <Card>
          <h2 className="font-mono text-[11px] uppercase tracking-ledger text-graphite-500">Risk meters (ledger)</h2>
          <dl className="mt-3 space-y-2 text-sm">
            <div className="flex justify-between">
              <dt className="text-graphite-500">Today credit vs cap</dt>
              <dd className="font-mono">
                {ov.data ? `${usd(ov.data.creditToday)} / ${usd(ov.data.capToday)}` : "—"}
              </dd>
            </div>
            <div className="flex justify-between">
              <dt className="text-graphite-500">License</dt>
              <dd>{ov.data?.user.license?.tier ?? "NONE"}</dd>
            </div>
            <div className="flex justify-between">
              <dt className="text-graphite-500">Paused books</dt>
              <dd>{books.filter((p) => p.paused).length}</dd>
            </div>
          </dl>
        </Card>
      </div>

      <Card>
        <h2 className="font-display text-lg">Open / fund</h2>
        <div className="mt-4 grid gap-4 md:grid-cols-2">
          <div>
            <Label>Amount USD</Label>
            <Input value={amount} onChange={(e) => setAmount(e.target.value)} />
            <Button
              className="mt-4"
              disabled={fund.isPending}
              onClick={() =>
                fund.mutate({
                  amountCents: String(Math.round(Number(amount) * 100)),
                  source,
                })
              }
            >
              Open new portfolio
            </Button>
            {fund.error ? <p className="mt-2 text-xs text-red-300">{fund.error.message}</p> : null}
          </div>
          <DepositSourcePicker value={source} onChange={setSource} />
        </div>
      </Card>

      {!books.length ? (
        <EmptyState title="No portfolios" body="Purchase a live license, then open a funded book. Minima are per license tier." />
      ) : (
        books.map((p) => {
          const util = Number(p.capCents) === 0 ? 0 : Math.round(Number((BigInt(p.principalCents) * 100n) / BigInt(p.capCents)));
          return (
            <Card key={p.id}>
              <div className="flex flex-wrap items-center justify-between gap-3">
                <div>
                  <div className="font-mono text-ember">{usd(p.principalCents)}</div>
                  <div className="text-xs text-graphite-500">
                    {p.licenseTier} · cap {usd(p.capCents)} · daily cap {p.dailyCapBps} bps (“up to”) · {p.status}
                    {p.paused ? " · PAUSED" : ""}
                  </div>
                  <div className="mt-1 font-mono text-[11px] text-graphite-500">
                    Cycle {new Date(p.cycleStart).toISOString().slice(0, 10)} → {new Date(p.cycleEnd).toISOString().slice(0, 10)}
                  </div>
                </div>
                <Button variant="outline" onClick={() => pause.mutate({ id: p.id, paused: !p.paused })}>
                  {p.paused ? "Resume" : "Pause"}
                </Button>
              </div>
              <div className="mt-3 h-1.5 bg-white/10">
                <div className="h-full bg-ember" style={{ width: `${Math.min(100, util)}%` }} />
              </div>
              <div className="mt-4 text-xs text-graphite-500">
                {p.fundings.map((f) => (
                  <div key={f.id} className="flex justify-between border-t border-white/[0.06] py-1 font-mono">
                    <span>
                      {f.source} {f.isNewPortfolio ? "NEW" : "TOP-UP"}
                    </span>
                    <span>{usd(f.amountCents)}</span>
                  </div>
                ))}
              </div>
              <div className="mt-3 flex gap-2">
                <Input
                  placeholder="Top-up USD"
                  className="max-w-[160px]"
                  onBlur={(e) => {
                    const n = Number(e.target.value);
                    if (n > 0) {
                      fund.mutate({
                        amountCents: String(Math.round(n * 100)),
                        source,
                        portfolioId: p.id,
                      });
                      e.target.value = "";
                    }
                  }}
                />
                <span className="self-center text-[10px] uppercase tracking-ledger text-graphite-500">blur to submit top-up</span>
              </div>
            </Card>
          );
        })
      )}
      {ov.data ? <Disclosure>{ov.data.copy.dailyCapDisclaimer}</Disclosure> : null}
    </div>
  );
}
