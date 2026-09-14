"use client";

import { Button, Card } from "@/components/ui";
import { trpc } from "@/trpc/client";

export default function JobsPage() {
  const q = trpc.admin.jobs.useQuery();
  const halt = trpc.admin.haltGet.useQuery();
  const setHalt = trpc.admin.halt.useMutation({ onSuccess: () => halt.refetch() });
  const run = trpc.admin.triggerJob.useMutation({ onSuccess: () => q.refetch() });
  const names = ["dailyCredits", "weeklySalary", "monthlyRanks", "fastStartClose", "licenseExpire", "withdrawExpire"] as const;
  return (
    <div className="space-y-4">
      <h1 className="text-2xl">Jobs</h1>
      <Card className="flex gap-3">
        <Button variant="danger" onClick={() => setHalt.mutate({ haltCredits: true })}>Halt credits</Button>
        <Button variant="danger" onClick={() => setHalt.mutate({ haltWithdraws: true })}>Halt withdraws</Button>
        <Button variant="outline" onClick={() => setHalt.mutate({ haltCredits: false, haltWithdraws: false })}>Clear halts</Button>
      </Card>
      <div className="flex flex-wrap gap-2">
        {names.map((n) => (
          <Button key={n} variant="outline" onClick={() => run.mutate({ name: n })}>
            {n}
          </Button>
        ))}
      </div>
      <pre className="overflow-auto text-[10px] text-slate-400">{JSON.stringify(q.data, null, 2)}</pre>
    </div>
  );
}
