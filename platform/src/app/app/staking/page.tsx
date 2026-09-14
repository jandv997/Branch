"use client";

import { Button, Card, Input, Label } from "@/components/ui";
import { trpc } from "@/trpc/client";
import { usd } from "@/lib/utils";
import { useState } from "react";

export default function StakingPage() {
  const lots = trpc.user.stakingLots.useQuery();
  const stake = trpc.user.stake.useMutation({ onSuccess: () => lots.refetch() });
  const unstake = trpc.user.unstake.useMutation({ onSuccess: () => lots.refetch() });
  const [amount, setAmount] = useState("100");
  return (
    <div className="space-y-6">
      <h1 className="text-2xl">Staking</h1>
      <Card className="border-cyan/20">
        <p className="text-sm text-cyan">Staking / referral / earnings wallet funding NEVER creates PSV, TV, tree, or Fast Start.</p>
        <div className="mt-4 max-w-xs">
          <Label>Move AVAILABLE → STAKING (USD)</Label>
          <Input value={amount} onChange={(e) => setAmount(e.target.value)} />
          <Button className="mt-3" onClick={() => stake.mutate({ amountCents: String(Math.round(Number(amount) * 100)) })}>
            Lock
          </Button>
        </div>
      </Card>
      {(lots.data ?? []).map((l) => (
        <Card key={l.id} className="flex items-center justify-between font-mono text-xs">
          <span>
            {usd(l.amountCents)} · locked until {new Date(l.lockedUntil).toISOString()}
          </span>
          {!l.releasedAt ? (
            <Button variant="outline" onClick={() => unstake.mutate({ lotId: l.id })}>
              Unlock
            </Button>
          ) : (
            <span>released</span>
          )}
        </Card>
      ))}
    </div>
  );
}
