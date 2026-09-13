"use client";

import { Card, Input } from "@/components/ui";
import { trpc } from "@/trpc/client";
import { usd } from "@/lib/utils";
import { useState } from "react";

export default function AdminLedger() {
  const [userId, setUserId] = useState("");
  const q = trpc.admin.ledgerSearch.useQuery({ userId: userId || undefined });
  return (
    <div className="space-y-4">
      <h1 className="text-2xl">Global ledger</h1>
      <Input placeholder="user id" value={userId} onChange={(e) => setUserId(e.target.value)} />
      <Card>
        <table className="w-full text-left text-xs">
          <tbody>
            {(q.data ?? []).map((r) => (
              <tr key={r.id} className="border-t border-white/10 font-mono">
                <td className="p-2">{r.user.email}</td>
                <td className="p-2">{r.wallet}</td>
                <td className="p-2">{r.direction}</td>
                <td className="p-2 text-cyan">{usd(r.amountCents)}</td>
                <td className="p-2">{r.type}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </Card>
    </div>
  );
}
