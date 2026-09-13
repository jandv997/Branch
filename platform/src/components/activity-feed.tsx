"use client";

import { usd } from "@/lib/utils";
import type { ActivityItem, ActivityKind } from "@/lib/activity";
import Link from "next/link";

const KIND_LABEL: Record<ActivityKind, string> = {
  CREDIT: "Credit",
  WALLET: "Wallet",
  TEAM: "Team",
  RANK: "Rank",
  FUNDING: "Funding",
  WITHDRAW: "Withdraw",
  DEPOSIT: "Deposit",
  SYSTEM: "System",
  JOB: "Job",
  AUDIT: "Audit",
};

export function ActivityFeed({
  items,
  empty = "No updates yet.",
}: {
  items: ActivityItem[];
  empty?: string;
}) {
  if (!items.length) {
    return <p className="text-sm text-slate-500">{empty}</p>;
  }
  return (
    <ol className="space-y-2">
      {items.map((i) => {
        const inner = (
          <div className="glass flex items-start justify-between gap-4 rounded-2xl p-4">
            <div className="min-w-0">
              <div className="flex items-center gap-2">
                <span className="rounded-md border border-cyan/30 px-1.5 py-0.5 font-mono text-[10px] uppercase tracking-ledger text-cyan">
                  {KIND_LABEL[i.kind]}
                </span>
                <span className="font-mono text-[10px] text-slate-500">{new Date(i.at).toISOString().replace("T", " ").slice(0, 19)}</span>
              </div>
              <div className="mt-1 truncate text-sm text-white">{i.title}</div>
              <p className="mt-1 line-clamp-2 text-xs text-slate-400">{i.detail}</p>
            </div>
            {i.amountCents !== undefined ? (
              <div className="shrink-0 font-mono text-sm text-cyan">{usd(i.amountCents)}</div>
            ) : null}
          </div>
        );
        return (
          <li key={i.id}>
            {i.href ? (
              <Link href={i.href} className="block hover:opacity-90">
                {inner}
              </Link>
            ) : (
              inner
            )}
          </li>
        );
      })}
    </ol>
  );
}

export const ACTIVITY_FILTERS: Array<ActivityKind | "ALL"> = [
  "ALL",
  "CREDIT",
  "FUNDING",
  "TEAM",
  "RANK",
  "WALLET",
  "DEPOSIT",
  "WITHDRAW",
  "SYSTEM",
  "JOB",
  "AUDIT",
];
