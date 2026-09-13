"use client";

import { Card, EmptyState } from "@/components/ui";
import { trpc } from "@/trpc/client";

export default function TeamPage() {
  const q = trpc.user.team.useQuery();
  if (!q.data) return <p className="text-slate-500">Loading team…</p>;
  return (
    <div className="space-y-6">
      <h1 className="text-2xl">Team L1–L7</h1>
      <p className="text-xs text-slate-500">
        PSV meter {q.data.psvMeterCents.toString()} cents · TV meter {q.data.tvMeterCents.toString()} cents · funded L1
        portfolios {q.data.fundedL1Count}
      </p>
      <div className="flex flex-wrap gap-2">
        {q.data.levels.map((lvl) => (
          <Card key={lvl.level} className="min-w-[160px] text-center">
            <div className="text-[10px] uppercase tracking-widest text-slate-500">L{lvl.level}</div>
            <div className="font-mono text-2xl text-cyan">{lvl.users.length}</div>
          </Card>
        ))}
      </div>
      {q.data.levels.every((l) => l.users.length === 0) ? (
        <EmptyState title="No downline" body="A passive user can operate with zero team. Volume always flows; pay compresses inactive." />
      ) : (
        q.data.levels.map((lvl) => (
          <Card key={`t-${lvl.level}`}>
            <h2 className="text-xs uppercase tracking-widest text-slate-500">Level {lvl.level}</h2>
            <table className="mt-2 w-full text-left text-xs">
              <tbody>
                {lvl.users.map((u) => (
                  <tr key={u.id} className="border-t border-white/10">
                    <td className="py-2">{u.email}</td>
                    <td>{u.rank}</td>
                    <td>{u.licenseStatus}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </Card>
        ))
      )}
    </div>
  );
}
