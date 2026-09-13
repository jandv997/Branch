"use client";

import { Button, Card, EmptyState } from "@/components/ui";
import { trpc } from "@/trpc/client";
import { usd } from "@/lib/utils";
import { useState } from "react";

export default function WalletsPage() {
  const w = trpc.user.wallets.useQuery();
  const ledger = trpc.user.ledger.useQuery({});
  const move = trpc.user.moveEarnings.useMutation();
  const [filter, setFilter] = useState<string>("");
  const rows = (ledger.data ?? []).filter((r) => !filter || r.wallet === filter);
  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <h1 className="text-2xl">Wallets</h1>
        <a href="/api/wallets/export" className="text-xs uppercase tracking-widest text-cyan">
          CSV export
        </a>
      </div>
      <div className="grid gap-3 md:grid-cols-5">
        {(w.data ?? []).map((x) => (
          <Card key={x.kind}>
            <div className="text-[10px] uppercase tracking-widest text-slate-500">{x.kind}</div>
            <div className="mt-2 font-mono text-xl text-cyan">{usd(x.balanceCents)}</div>
          </Card>
        ))}
      </div>
      <Card className="flex items-center gap-3">
        <Button onClick={() => move.mutate({ amountCents: "10000" })}>Move $100 EARNINGS → AVAILABLE</Button>
        <p className="text-xs text-slate-500">Weekly withdraw is from AVAILABLE only.</p>
      </Card>
      <div className="flex gap-2 text-[10px] uppercase tracking-widest">
        {["", "AVAILABLE", "EARNINGS", "REFERRAL", "STAKING", "PENDING"].map((k) => (
          <button key={k || "all"} className="rounded border border-white/10 px-2 py-1" onClick={() => setFilter(k)}>
            {k || "all"}
          </button>
        ))}
      </div>
      {!rows.length ? (
        <EmptyState title="No ledger rows" body="Funding, credits, and withdrawals write immutable ledger entries." />
      ) : (
        <div className="overflow-x-auto">
          <table className="w-full text-left text-xs">
            <thead className="uppercase tracking-widest text-slate-500">
              <tr>
                <th className="p-2">Time</th>
                <th className="p-2">Wallet</th>
                <th className="p-2">Dir</th>
                <th className="p-2">Amount</th>
                <th className="p-2">Type</th>
              </tr>
            </thead>
            <tbody>
              {rows.map((r) => (
                <tr key={r.id} className="border-t border-white/10 font-mono">
                  <td className="p-2 text-slate-500">{new Date(r.createdAt).toISOString()}</td>
                  <td className="p-2">{r.wallet}</td>
                  <td className="p-2">{r.direction}</td>
                  <td className="p-2 text-cyan">{usd(r.amountCents)}</td>
                  <td className="p-2">{r.type}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}
    </div>
  );
}
