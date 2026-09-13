"use client";

import { Button, Card, DepositSourcePicker, EmptyState, Input, Label } from "@/components/ui";
import { trpc } from "@/trpc/client";
import { usd } from "@/lib/utils";
import { useState } from "react";
import type { FundingSource } from "@/domain/comp/config";

export default function PortfoliosPage() {
  const q = trpc.user.portfolios.useQuery();
  const utils = trpc.useUtils();
  const fund = trpc.user.fundPortfolio.useMutation({ onSuccess: () => utils.user.portfolios.invalidate() });
  const pause = trpc.user.pausePortfolio.useMutation({ onSuccess: () => utils.user.portfolios.invalidate() });
  const [amount, setAmount] = useState("500");
  const [source, setSource] = useState<FundingSource>("DIRECT_DEPOSIT");
  if (q.isError) return <Card className="text-red-300">{q.error.message}</Card>;
  return (
    <div className="space-y-6">
      <h1 className="text-2xl">Portfolios</h1>
      <Card>
        <h2 className="text-sm uppercase tracking-widest text-slate-400">Open / fund</h2>
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
      {!q.data?.length ? (
        <EmptyState title="No portfolios" body="Purchase a live license, then open a funded book. Minima are per license tier." />
      ) : (
        q.data.map((p) => (
          <Card key={p.id}>
            <div className="flex flex-wrap items-center justify-between gap-3">
              <div>
                <div className="font-mono text-cyan">{usd(p.principalCents)}</div>
                <div className="text-xs text-slate-500">
                  {p.licenseTier} · cap {usd(p.capCents)} · daily cap {p.dailyCapBps} bps (“up to”) · {p.status}
                  {p.paused ? " · PAUSED" : ""}
                </div>
              </div>
              <Button variant="outline" onClick={() => pause.mutate({ id: p.id, paused: !p.paused })}>
                {p.paused ? "Resume" : "Pause"}
              </Button>
            </div>
            <div className="mt-4 text-xs text-slate-500">
              {p.fundings.map((f) => (
                <div key={f.id} className="flex justify-between border-t border-white/5 py-1 font-mono">
                  <span>{f.source} {f.isNewPortfolio ? "NEW" : "TOP-UP"}</span>
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
              <span className="self-center text-[10px] uppercase tracking-widest text-slate-500">blur to submit top-up</span>
            </div>
          </Card>
        ))
      )}
    </div>
  );
}
