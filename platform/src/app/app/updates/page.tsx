"use client";

import { Card, EmptyState } from "@/components/ui";
import { trpc } from "@/trpc/client";
import Link from "next/link";

export default function BoUpdatesPage() {
  const a = trpc.user.announcements.useQuery();
  const n = trpc.user.notifications.useQuery();
  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <h1 className="text-2xl">Updates</h1>
        <Link href="/updates" className="text-xs uppercase tracking-widest text-cyan">
          Public changelog + job runs →
        </Link>
      </div>
      <p className="text-xs text-slate-500">
        Announcements and account notifications. Compensation still comes only from the backend policy module.
      </p>
      {(a.data ?? []).map((x) => (
        <Card key={x.id}>
          <div className="text-[10px] uppercase tracking-widest text-slate-500">{new Date(x.createdAt).toISOString()}</div>
          <div className="mt-1 text-sm">{x.title}</div>
          <p className="text-xs text-slate-400">{x.body}</p>
        </Card>
      ))}
      {(n.data ?? []).map((x) => (
        <Card key={x.id}>
          <div className="text-sm">{x.title}</div>
          <p className="text-xs text-slate-400">{x.body}</p>
        </Card>
      ))}
      {!(a.data ?? []).length && !(n.data ?? []).length ? (
        <EmptyState title="No updates yet" body="When admin publishes an announcement it appears here and on /updates." />
      ) : null}
    </div>
  );
}
