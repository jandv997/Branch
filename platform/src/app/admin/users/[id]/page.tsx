"use client";

import { Button, Card, Input, Label, Select, Textarea } from "@/components/ui";
import { trpc } from "@/trpc/client";
import { usd } from "@/lib/utils";
import { useParams } from "next/navigation";
import { useState } from "react";

export default function GodProfile() {
  const params = useParams<{ id: string }>();
  const id = params.id;
  const q = trpc.admin.user.useQuery({ id });
  const utils = trpc.useUtils();
  const upd = trpc.admin.updateUser.useMutation({ onSuccess: () => q.refetch() });
  const freeze = trpc.admin.freeze.useMutation({ onSuccess: () => q.refetch() });
  const rank = trpc.admin.setRank.useMutation({ onSuccess: () => q.refetch() });
  const sponsor = trpc.admin.setSponsor.useMutation({ onSuccess: () => q.refetch() });
  const lic = trpc.admin.setLicense.useMutation({ onSuccess: () => q.refetch() });
  const reset2fa = trpc.admin.reset2fa.useMutation();
  const adj = trpc.admin.adjustBalance.useMutation({ onSuccess: () => q.refetch() });
  const post = trpc.admin.postComp.useMutation({ onSuccess: () => q.refetch() });
  const imp = trpc.admin.impersonate.useMutation({ onSuccess: () => (window.location.href = "/app") });
  const [reason, setReason] = useState("admin adjustment");
  const [amt, setAmt] = useState("10");
  if (!q.data) return <p className="text-slate-500">Loading GOD profile…</p>;
  const u = q.data;
  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <h1 className="text-2xl">GOD · {u.email}</h1>
        <Button variant="danger" onClick={() => imp.mutate({ userId: u.id })}>
          View-as
        </Button>
      </div>
      <Card className="grid gap-3 md:grid-cols-2 text-sm">
        <div>
          <Label>Email</Label>
          <Input defaultValue={u.email} onBlur={(e) => upd.mutate({ id, email: e.target.value })} />
        </div>
        <div>
          <Label>Notes</Label>
          <Textarea defaultValue={u.notes ?? ""} onBlur={(e) => upd.mutate({ id, notes: e.target.value })} />
        </div>
        <div className="flex gap-2">
          <Button variant="outline" onClick={() => freeze.mutate({ id, frozen: u.status !== "FROZEN", reason })}>
            {u.status === "FROZEN" ? "Unfreeze" : "Freeze"}
          </Button>
          <Button variant="outline" onClick={() => reset2fa.mutate({ id, reason })}>
            Reset 2FA
          </Button>
        </div>
        <div>
          <Label>Force rank</Label>
          <Select defaultValue={u.rank} onChange={(e) => rank.mutate({ id, rank: e.target.value as typeof u.rank, reason })}>
            {["NONE","ASSOCIATE","CONSULTANT","STRATEGIST","TEAM_LEAD","MANAGER","SENIOR_MANAGER","DIRECTOR","SENIOR_DIRECTOR","EXECUTIVE","AMBASSADOR","SOVEREIGN","LEGACY"].map((r) => (
              <option key={r}>{r}</option>
            ))}
          </Select>
        </div>
        <div>
          <Label>Sponsor id (rebuild tree)</Label>
          <Input defaultValue={u.sponsorId ?? ""} onBlur={(e) => sponsor.mutate({ id, sponsorId: e.target.value || null, reason })} />
        </div>
        <div>
          <Label>License</Label>
          <Select
            defaultValue={u.license?.tier ?? ""}
            onChange={(e) => lic.mutate({ id, tier: (e.target.value || null) as "PULSE" | null, reason })}
          >
            <option value="">NONE</option>
            {["PULSE", "CORE", "APEX", "PRIME"].map((t) => (
              <option key={t}>{t}</option>
            ))}
          </Select>
        </div>
      </Card>
      <Card>
        <h2 className="text-sm">Wallets — edit with reason (ledger + audit)</h2>
        <Input className="mt-2" value={reason} onChange={(e) => setReason(e.target.value)} />
        <div className="mt-3 grid gap-2 md:grid-cols-5">
          {u.wallets.map((w) => (
            <div key={w.kind} className="rounded border border-white/10 p-3">
              <div className="text-[10px] uppercase tracking-widest text-slate-500">{w.kind}</div>
              <div className="font-mono text-cyan">{usd(w.balanceCents)}</div>
              <Input className="mt-2" value={amt} onChange={(e) => setAmt(e.target.value)} />
              <div className="mt-2 flex gap-1">
                <Button
                  variant="outline"
                  onClick={() =>
                    adj.mutate({
                      userId: u.id,
                      wallet: w.kind,
                      direction: "CREDIT",
                      amountCents: String(Math.round(Number(amt) * 100)),
                      reason,
                    })
                  }
                >
                  +
                </Button>
                <Button
                  variant="outline"
                  onClick={() =>
                    adj.mutate({
                      userId: u.id,
                      wallet: w.kind,
                      direction: "DEBIT",
                      amountCents: String(Math.round(Number(amt) * 100)),
                      reason,
                    })
                  }
                >
                  −
                </Button>
              </div>
            </div>
          ))}
        </div>
      </Card>
      <Card>
        <h2 className="text-sm">Manual post / reverse compensation</h2>
        <div className="mt-3 flex flex-wrap gap-2">
          {(["TREE_COMMISSION", "FAST_START", "RANK_BONUS", "SALARY", "DAILY_CREDIT"] as const).map((k) => (
            <Button
              key={k}
              variant="outline"
              onClick={() =>
                post.mutate({ userId: u.id, kind: k, amountCents: String(Math.round(Number(amt) * 100)), reason })
              }
            >
              Post {k}
            </Button>
          ))}
        </div>
      </Card>
      <Card>
        <h2 className="text-sm">Portfolios</h2>
        {u.portfolios.map((p) => (
          <div key={p.id} className="mt-2 font-mono text-xs">
            {p.licenseTier} {usd(p.principalCents)} / cap {usd(p.capCents)} paused={String(p.paused)}
          </div>
        ))}
      </Card>
    </div>
  );
}
