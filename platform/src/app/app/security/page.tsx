"use client";

import { Button, Card, Input, Label } from "@/components/ui";
import { trpc } from "@/trpc/client";
import { useState } from "react";

export default function SecurityPage() {
  const me = trpc.auth.me.useQuery();
  const sessions = trpc.auth.sessions.useQuery();
  const ip = trpc.user.ipLog.useQuery();
  const wl = trpc.user.whitelist.useQuery();
  const setup = trpc.auth.totpSetup.useMutation();
  const verify = trpc.auth.totpVerify.useMutation({ onSuccess: () => me.refetch() });
  const add = trpc.user.addWhitelist.useMutation({ onSuccess: () => wl.refetch() });
  const [code, setCode] = useState("");
  const [addr, setAddr] = useState("");
  return (
    <div className="space-y-6">
      <h1 className="text-2xl">Security</h1>
      <Card>
        <h2 className="text-sm">2FA {me.data?.totpEnabled ? "enabled" : "off"}</h2>
        <Button className="mt-3" variant="outline" onClick={() => setup.mutate()}>
          Begin TOTP setup
        </Button>
        {setup.data ? (
          <div className="mt-3 text-xs">
            <p className="font-mono break-all">{setup.data.uri}</p>
            <Label>Code</Label>
            <Input value={code} onChange={(e) => setCode(e.target.value)} />
            <Button className="mt-2" onClick={() => verify.mutate({ code })}>
              Verify
            </Button>
            {verify.data?.backupCodes ? (
              <pre className="mt-3 text-[10px]">{verify.data.backupCodes.join("\n")}</pre>
            ) : null}
          </div>
        ) : null}
      </Card>
      <Card>
        <h2 className="text-sm">Whitelist (+24h lock)</h2>
        <Input className="mt-3" placeholder="address" value={addr} onChange={(e) => setAddr(e.target.value)} />
        <Button className="mt-2" onClick={() => add.mutate({ network: "TRC20", address: addr })}>
          Add TRC20
        </Button>
        <ul className="mt-3 space-y-1 font-mono text-xs">
          {(wl.data ?? []).map((a) => (
            <li key={a.id}>
              {a.address} · unlocks {new Date(a.unlockedAt).toISOString()}
            </li>
          ))}
        </ul>
      </Card>
      <Card>
        <h2 className="text-sm">Sessions</h2>
        {(sessions.data ?? []).map((s) => (
          <div key={s.id} className="mt-2 font-mono text-xs text-slate-400">
            {s.ip} · {s.userAgent}
          </div>
        ))}
      </Card>
      <Card>
        <h2 className="text-sm">IP log</h2>
        {(ip.data ?? []).map((r) => (
          <div key={r.id} className="mt-1 font-mono text-xs text-slate-500">
            {r.action} {r.ip}
          </div>
        ))}
      </Card>
    </div>
  );
}
