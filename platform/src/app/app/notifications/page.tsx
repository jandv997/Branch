"use client";

import { Card, EmptyState } from "@/components/ui";
import { trpc } from "@/trpc/client";

export default function NotificationsPage() {
  const q = trpc.user.notifications.useQuery();
  const a = trpc.user.announcements.useQuery();
  return (
    <div className="space-y-6">
      <h1 className="text-2xl">Notifications</h1>
      {(a.data ?? []).map((x) => (
        <Card key={x.id}>
          <div className="text-sm">{x.title}</div>
          <p className="text-xs text-slate-400">{x.body}</p>
        </Card>
      ))}
      {!(q.data ?? []).length ? (
        <EmptyState title="No notifications" body="System and admin announcements appear here." />
      ) : (
        q.data!.map((n) => (
          <Card key={n.id}>
            <div className="text-sm">{n.title}</div>
            <p className="text-xs text-slate-400">{n.body}</p>
          </Card>
        ))
      )}
    </div>
  );
}
