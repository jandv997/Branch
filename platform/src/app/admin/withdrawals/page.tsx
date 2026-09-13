"use client";

import { Button, Card } from "@/components/ui";
import { trpc } from "@/trpc/client";
import { usd } from "@/lib/utils";

export default function AdminWithdrawals() {
  const q = trpc.admin.withdrawals.useQuery({});
  const d = trpc.admin.decideWithdraw.useMutation({ onSuccess: () => q.refetch() });
  const batch = trpc.admin.batchWithdraw.useMutation({ onSuccess: () => q.refetch() });
  const ids = (q.data ?? []).filter((w) => w.status === "PENDING").map((w) => w.id);
  return (
    <div className="space-y-4">
      <div className="flex items-center justify-between">
        <h1 className="text-2xl">Withdrawal queue</h1>
        <Button variant="outline" onClick={() => batch.mutate({ ids, action: "APPROVE" })}>
          Batch approve pending
        </Button>
      </div>
      {(q.data ?? []).map((w) => (
        <Card key={w.id} className="flex flex-wrap items-center justify-between gap-3 text-xs">
          <div>
            <div className="font-mono">{w.user.email} · {usd(w.amountCents)} · {w.status}</div>
            <div className="text-slate-500">{w.network} {w.address}</div>
          </div>
          <div className="flex gap-2">
            <Button variant="outline" onClick={() => d.mutate({ id: w.id, action: "APPROVE" })}>Approve</Button>
            <Button variant="outline" onClick={() => d.mutate({ id: w.id, action: "MARK_PAID" })}>Mark paid</Button>
            <Button variant="danger" onClick={() => d.mutate({ id: w.id, action: "REJECT", reason: "rejected" })}>Reject</Button>
          </div>
        </Card>
      ))}
    </div>
  );
}
