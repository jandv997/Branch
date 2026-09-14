"use client";

import { Card, Input } from "@/components/ui";
import { trpc } from "@/trpc/client";
import { useState } from "react";

export default function AuditPage() {
  const [q, setQ] = useState("");
  const list = trpc.admin.audit.useQuery({ q: q || undefined });
  return (
    <div className="space-y-4">
      <h1 className="text-2xl">Audit explorer</h1>
      <Input placeholder="action / entity / subject" value={q} onChange={(e) => setQ(e.target.value)} />
      {(list.data ?? []).map((a) => (
        <Card key={a.id} className="text-xs">
          <div className="font-mono text-cyan">{a.action}</div>
          <div className="text-slate-500">
            {a.entity} {a.entityId} · {a.reason} · {a.ip}
          </div>
          <pre className="mt-2 overflow-auto text-[10px]">{JSON.stringify({ before: a.before, after: a.after }, null, 2)}</pre>
        </Card>
      ))}
    </div>
  );
}
