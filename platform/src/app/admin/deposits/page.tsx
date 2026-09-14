"use client";

import { Button, Card } from "@/components/ui";
import { trpc } from "@/trpc/client";
import { usd } from "@/lib/utils";

export default function AdminDeposits() {
  const q = trpc.admin.deposits.useQuery();
  const a = trpc.admin.assignDeposit.useMutation({ onSuccess: () => q.refetch() });
  return (
    <div className="space-y-4">
      <h1 className="text-2xl">Deposit matcher</h1>
      {(q.data ?? []).map((d) => (
        <Card key={d.id} className="flex items-center justify-between text-xs">
          <div>
            <div className="font-mono">{d.memo} · {usd(d.amountCents)} · {d.status}</div>
            <div className="text-slate-500">{d.user.email}</div>
          </div>
          {d.status !== "CONFIRMED" ? (
            <Button onClick={() => a.mutate({ id: d.id, confirm: true })}>Confirm / assign</Button>
          ) : null}
        </Card>
      ))}
    </div>
  );
}
