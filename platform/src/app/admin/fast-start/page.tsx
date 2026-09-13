"use client";

import { Button, Card } from "@/components/ui";
import { trpc } from "@/trpc/client";

export default function AdminFs() {
  const q = trpc.admin.fsMonitor.useQuery();
  const close = trpc.admin.fsForceClose.useMutation({ onSuccess: () => q.refetch() });
  return (
    <div className="space-y-4">
      <h1 className="text-2xl">Fast Start live window</h1>
      {(q.data?.live ?? []).map((u) => (
        <Card key={u.id} className="flex items-center justify-between text-sm">
          <span>
            {u.email} · activated {u.activatedAt ? new Date(u.activatedAt).toISOString() : "—"}
          </span>
          <Button variant="danger" onClick={() => close.mutate({ userId: u.id, reason: "force close" })}>
            Force close
          </Button>
        </Card>
      ))}
    </div>
  );
}
