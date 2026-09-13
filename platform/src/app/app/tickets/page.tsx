"use client";

import { Button, Card, Input, Label, Textarea } from "@/components/ui";
import { trpc } from "@/trpc/client";
import { useState } from "react";

export default function TicketsPage() {
  const q = trpc.user.tickets.useQuery();
  const open = trpc.user.openTicket.useMutation({ onSuccess: () => q.refetch() });
  const [subject, setSubject] = useState("");
  const [body, setBody] = useState("");
  return (
    <div className="space-y-6">
      <h1 className="text-2xl">Tickets</h1>
      <Card className="max-w-lg space-y-3">
        <div>
          <Label>Subject</Label>
          <Input value={subject} onChange={(e) => setSubject(e.target.value)} />
        </div>
        <div>
          <Label>Body</Label>
          <Textarea value={body} onChange={(e) => setBody(e.target.value)} />
        </div>
        <Button onClick={() => open.mutate({ subject, body })}>Open</Button>
      </Card>
      {(q.data ?? []).map((t) => (
        <Card key={t.id}>
          <div className="text-sm">{t.subject} · {t.status}</div>
          {t.messages.map((m) => (
            <p key={m.id} className="mt-2 text-xs text-slate-400">{m.body}</p>
          ))}
        </Card>
      ))}
    </div>
  );
}
