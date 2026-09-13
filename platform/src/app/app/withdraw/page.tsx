"use client";

import { Button, Card, Input, Label, Select, TwoFAGate } from "@/components/ui";
import { trpc } from "@/trpc/client";
import { usd } from "@/lib/utils";
import { useState } from "react";

export default function WithdrawPage() {
  const list = trpc.user.withdrawals.useQuery();
  const wl = trpc.user.whitelist.useQuery();
  const me = trpc.auth.me.useQuery();
  const req = trpc.user.requestWithdraw.useMutation({ onSuccess: () => list.refetch() });
  const cancel = trpc.user.cancelWithdraw.useMutation({ onSuccess: () => list.refetch() });
  const [amount, setAmount] = useState("50");
  const [totp, setTotp] = useState("");
  const [address, setAddress] = useState("");
  const [network, setNetwork] = useState("TRC20");
  return (
    <div className="space-y-6">
      <h1 className="text-2xl">Withdraw</h1>
      <Card>
        <p className="text-sm text-slate-400">From AVAILABLE only. 2FA mandatory. Whitelist + 24h lock. KYC above threshold.</p>
        {!me.data?.totpEnabled ? (
          <p className="mt-3 text-sm text-amber-300">Enable 2FA in Security before the first withdraw.</p>
        ) : (
          <div className="mt-4 grid max-w-lg gap-3">
            <div>
              <Label>Amount USD</Label>
              <Input value={amount} onChange={(e) => setAmount(e.target.value)} />
            </div>
            <div>
              <Label>Network</Label>
              <Select value={network} onChange={(e) => setNetwork(e.target.value)}>
                <option>TRC20</option>
                <option>ERC20</option>
              </Select>
            </div>
            <div>
              <Label>Whitelist address</Label>
              <Select value={address} onChange={(e) => setAddress(e.target.value)}>
                <option value="">Select</option>
                {(wl.data ?? []).map((a) => (
                  <option key={a.id} value={a.address}>
                    {a.address} {new Date(a.unlockedAt) > new Date() ? "(locked)" : ""}
                  </option>
                ))}
              </Select>
            </div>
            <TwoFAGate value={totp} onChange={setTotp} />
            <Button
              disabled={req.isPending}
              onClick={() =>
                req.mutate({
                  amountCents: String(Math.round(Number(amount) * 100)),
                  network,
                  address,
                  totp,
                })
              }
            >
              Request
            </Button>
            {req.error ? <p className="text-xs text-red-300">{req.error.message}</p> : null}
          </div>
        )}
      </Card>
      {(list.data ?? []).map((w) => (
        <Card key={w.id} className="font-mono text-xs">
          <div className="flex justify-between">
            <span>{w.status}</span>
            <span className="text-cyan">{usd(w.amountCents)}</span>
          </div>
          <div className="mt-1 text-slate-500">{w.network} {w.address}</div>
          {w.status === "PENDING" ? (
            <Button className="mt-3" variant="outline" onClick={() => cancel.mutate({ id: w.id })}>
              Cancel
            </Button>
          ) : null}
        </Card>
      ))}
    </div>
  );
}
