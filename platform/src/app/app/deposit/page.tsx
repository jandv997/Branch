"use client";

import { Button, Card, Input, Label } from "@/components/ui";
import { trpc } from "@/trpc/client";
import { usd } from "@/lib/utils";
import { useState } from "react";

export default function DepositPage() {
  const list = trpc.user.deposits.useQuery();
  const create = trpc.user.createDeposit.useMutation({ onSuccess: () => list.refetch() });
  const [amount, setAmount] = useState("500");
  return (
    <div className="space-y-6">
      <h1 className="text-2xl">Direct deposit</h1>
      <Card>
        <p className="text-sm text-slate-400">
          DIRECT_DEPOSIT is the only source that can create PSV, TV, tree, and Fast Start. Dev adapter confirms instantly
          and opens/funds a portfolio.
        </p>
        <div className="mt-4 max-w-xs">
          <Label>Amount USD</Label>
          <Input value={amount} onChange={(e) => setAmount(e.target.value)} />
          <Button
            className="mt-3"
            disabled={create.isPending}
            onClick={() => create.mutate({ amountCents: String(Math.round(Number(amount) * 100)) })}
          >
            Create memo / simulate
          </Button>
          {create.error ? <p className="mt-2 text-xs text-red-300">{create.error.message}</p> : null}
        </div>
      </Card>
      {(list.data ?? []).map((d) => (
        <Card key={d.id} className="font-mono text-xs">
          <div className="flex justify-between">
            <span>{d.memo}</span>
            <span className="text-cyan">{usd(d.amountCents)}</span>
          </div>
          <div className="mt-2 text-slate-500">
            {d.status} · {d.network} · {d.address}
          </div>
        </Card>
      ))}
    </div>
  );
}
