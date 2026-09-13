"use client";

import { ActivityFeed, ACTIVITY_FILTERS } from "@/components/activity-feed";
import { Card } from "@/components/ui";
import type { ActivityKind } from "@/lib/activity";
import { trpc } from "@/trpc/client";
import { useMemo, useState } from "react";

export default function AdminUpdatesPage() {
  const q = trpc.admin.activity.useQuery({ take: 80 });
  const [filter, setFilter] = useState<ActivityKind | "ALL">("ALL");
  const items = useMemo(() => {
    const all = q.data ?? [];
    return filter === "ALL" ? all : all.filter((i) => i.kind === filter);
  }, [q.data, filter]);
  return (
    <div className="space-y-6">
      <div>
        <h1 className="text-2xl">Updates</h1>
        <p className="text-xs text-slate-500">Jobs, audit, and announcements — the operational feed for this environment.</p>
      </div>
      <div className="flex flex-wrap gap-2">
        {ACTIVITY_FILTERS.filter((k) => k === "ALL" || k === "JOB" || k === "AUDIT" || k === "SYSTEM").map((k) => (
          <button
            key={k}
            onClick={() => setFilter(k)}
            className={`rounded-md border px-2 py-1 text-[10px] uppercase tracking-widest ${
              filter === k ? "border-cyan text-cyan" : "border-white/10 text-slate-400"
            }`}
          >
            {k}
          </button>
        ))}
      </div>
      {q.isError ? <Card className="text-red-300">{q.error.message}</Card> : null}
      {q.isLoading ? <p className="text-slate-500">Loading…</p> : <ActivityFeed items={items} />}
    </div>
  );
}
