"use client";

import { ActivityFeed, ACTIVITY_FILTERS } from "@/components/activity-feed";
import { Card } from "@/components/ui";
import type { ActivityKind } from "@/lib/activity";
import { trpc } from "@/trpc/client";
import { useMemo, useState } from "react";

export default function BoUpdatesPage() {
  const q = trpc.user.activity.useQuery({ take: 80 });
  const [filter, setFilter] = useState<ActivityKind | "ALL">("ALL");
  const items = useMemo(() => {
    const all = q.data ?? [];
    return filter === "ALL" ? all : all.filter((i) => i.kind === filter);
  }, [q.data, filter]);
  return (
    <div className="space-y-6">
      <div>
        <p className="kicker">Updates</p>
        <h1 className="mt-2 font-display text-3xl font-semibold">Activity timeline</h1>
        <p className="text-xs text-slate-500">
          Live activity on this account: daily credits (up to cap), funding, tree/FS, ranks, deposits, withdrawals, and system notices.
        </p>
      </div>
      <div className="flex flex-wrap gap-2">
        {ACTIVITY_FILTERS.filter((k) => k !== "JOB" && k !== "AUDIT").map((k) => (
          <button
            key={k}
            onClick={() => setFilter(k)}
            className={`rounded-md border px-2 py-1 text-[10px] uppercase tracking-widest ${
              filter === k ? "border-ember text-ember" : "border-white/10 text-graphite-400"
            }`}
          >
            {k}
          </button>
        ))}
      </div>
      {q.isError ? <Card className="text-red-300">{q.error.message}</Card> : null}
      {q.isLoading ? <p className="text-slate-500">Loading activity…</p> : <ActivityFeed items={items} />}
    </div>
  );
}
