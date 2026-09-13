"use client";

import { Button, Card } from "@/components/ui";
import { trpc } from "@/trpc/client";

export default function AdminRanks() {
  const jobs = trpc.admin.jobs.useQuery();
  const run = trpc.admin.triggerJob.useMutation({ onSuccess: () => jobs.refetch() });
  return (
    <div className="space-y-4">
      <h1 className="text-2xl">Rank settlement</h1>
      <p className="text-sm text-slate-400">Monthly keep/hold/drop + weekly salary. Rank-up spillover applies on each DD volume credit, not only at month end.</p>
      <div className="flex gap-2">
        <Button onClick={() => run.mutate({ name: "monthlyRanks" })}>Run monthlyRanks</Button>
        <Button variant="outline" onClick={() => run.mutate({ name: "weeklySalary" })}>Run weeklySalary</Button>
      </div>
      <Card>
        <pre className="overflow-auto text-[10px]">{JSON.stringify(jobs.data, null, 2)}</pre>
      </Card>
    </div>
  );
}
