"use client";

import { Button, Card, Textarea } from "@/components/ui";
import { trpc } from "@/trpc/client";
import { useState } from "react";

export default function AdminTickets() {
  const q = trpc.admin.tickets.useQuery();
  const reply = trpc.admin.replyTicket.useMutation({ onSuccess: () => q.refetch() });
  const [body, setBody] = useState("Working on this.");
  return (
    <div className="space-y-4">
      <h1 className="text-2xl">Tickets · support</h1>
      {(q.data ?? []).map((t) => (
        <Card key={t.id}>
          <div className="text-sm">{t.subject} · {t.user.email} · {t.status}</div>
          <Textarea className="mt-2" value={body} onChange={(e) => setBody(e.target.value)} />
          <Button className="mt-2" onClick={() => reply.mutate({ id: t.id, body, status: "PENDING" })}>
            Reply
          </Button>
        </Card>
      ))}
    </div>
  );
}
