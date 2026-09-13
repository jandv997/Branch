"use client";

import { Button, Card } from "@/components/ui";
import { trpc } from "@/trpc/client";
import { useState } from "react";

export default function KycPage() {
  const q = trpc.user.kycDocs.useQuery();
  const [msg, setMsg] = useState<string | null>(null);
  async function upload(file: File) {
    const fd = new FormData();
    fd.set("file", file);
    fd.set("kind", "id");
    const res = await fetch("/api/kyc/upload", { method: "POST", body: fd });
    setMsg(res.ok ? "Uploaded" : "Upload failed");
    q.refetch();
  }
  return (
    <div className="space-y-6">
      <h1 className="text-2xl">KYC</h1>
      <Card>
        <p className="text-sm text-slate-400">Status: {q.data?.status ?? "…"}</p>
        <input className="mt-4 text-sm" type="file" onChange={(e) => e.target.files?.[0] && upload(e.target.files[0])} />
        {msg ? <p className="mt-2 text-xs text-cyan">{msg}</p> : null}
      </Card>
      {(q.data?.docs ?? []).map((d) => (
        <Card key={d.id} className="text-xs">
          {d.kind} · {d.status} · {d.storageKey}
        </Card>
      ))}
    </div>
  );
}
