"use client";

import { Card, Input } from "@/components/ui";
import { trpc } from "@/trpc/client";
import Link from "next/link";
import { useState } from "react";

export default function AdminUsers() {
  const [q, setQ] = useState("");
  const list = trpc.admin.users.useQuery({ q: q || undefined });
  return (
    <div className="space-y-4">
      <h1 className="text-2xl">Users</h1>
      <Input placeholder="email or id" value={q} onChange={(e) => setQ(e.target.value)} />
      {(list.data ?? []).map((u) => (
        <Link key={u.id} href={`/admin/users/${u.id}`}>
          <Card className="mb-2 flex justify-between text-sm hover:border-cyan/30">
            <span>{u.email}</span>
            <span className="font-mono text-xs text-slate-500">
              {u.role} · {u.status} · {u.rank}
            </span>
          </Card>
        </Link>
      ))}
    </div>
  );
}
