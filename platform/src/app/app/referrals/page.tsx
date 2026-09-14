"use client";

import { Card, EmptyState } from "@/components/ui";
import { trpc } from "@/trpc/client";
import { WHY_NOT_PAID_COPY, type WhyNotPaidCode } from "@/domain/comp/events";
import { usd } from "@/lib/utils";

export default function ReferralsPage() {
  const q = trpc.user.referrals.useQuery();
  const rows = q.data ?? [];
  return (
    <div className="space-y-6">
      <div>
        <p className="kicker">Network</p>
        <h1 className="mt-2 font-display text-3xl font-semibold">Commission ledger</h1>
      </div>
      <p className="text-xs text-graphite-500">Tree payouts and why-not-paid reason codes from the policy module.</p>
      {!rows.length ? (
        <EmptyState title="No referral events" body="Tree pays only on NEW PORTFOLIO + DIRECT_DEPOSIT and the first license fee." />
      ) : (
        <div className="overflow-x-auto">
          <table className="w-full text-left text-xs">
            <thead className="uppercase tracking-widest text-slate-500">
              <tr>
                <th className="p-2">When</th>
                <th className="p-2">Level</th>
                <th className="p-2">Amount</th>
                <th className="p-2">Reason</th>
              </tr>
            </thead>
            <tbody>
              {rows.map((r) => (
                <tr key={r.id} className="border-t border-white/10">
                  <td className="p-2 font-mono text-slate-500">{new Date(r.createdAt).toISOString()}</td>
                  <td className="p-2">{r.level}</td>
                  <td className="p-2 font-mono text-ember">{usd(r.amountCents)}</td>
                  <td className="p-2 text-slate-400">
                    {r.reasonCode} — {WHY_NOT_PAID_COPY[(r.reasonCode as WhyNotPaidCode) ?? "PAID"] ?? r.reasonCode}
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}
    </div>
  );
}
