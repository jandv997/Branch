import { PublicChrome, PatentBadge } from "@/components/brand";
import { loadConfig } from "@/server/load-config";
import { usd } from "@/lib/utils";

export default async function RanksPage() {
  const cfg = await loadConfig();
  return (
    <PublicChrome>
      <main className="mx-auto max-w-6xl px-5 py-16">
        <PatentBadge />
        <h1 className="mt-4 font-display text-4xl tracking-tight">Ranks</h1>
        <p className="mt-3 max-w-3xl text-ink-500">
          Any personal license qualifies all ranks. Rank-up meters consume quota and spill remainder into the next rank.
          Monthly keep TV is a separate meter. Miss 1 month: stop salary, hold 30 days. Miss 2nd: drop one rank. One-time
          bonus never pays twice for the same rank. Rank salary posts to {cfg.rankPayoutWallet}.
        </p>
        <div className="mt-8 overflow-x-auto border border-ink-900/12 bg-paper-50">
          <table className="w-full text-left text-xs">
            <thead className="font-mono uppercase tracking-ledger text-ink-400">
              <tr>
                <th className="p-3">Rank</th>
                <th className="p-3">PSV L1</th>
                <th className="p-3">Rank-up TV L1–L7</th>
                <th className="p-3">Monthly keep TV</th>
                <th className="p-3">One-time</th>
                <th className="p-3">Weekly</th>
              </tr>
            </thead>
            <tbody>
              {cfg.ranks.filter((r) => r.code !== "NONE").map((r) => (
                <tr key={r.code} className="border-t border-ink-900/10 font-mono">
                  <td className="p-3 text-ink-900">{r.name}</td>
                  <td className="p-3">{usd(r.psvCents)}</td>
                  <td className="p-3">{usd(r.rankUpTvCents)}</td>
                  <td className="p-3">{usd(r.keepTvCents)}</td>
                  <td className="p-3 text-copper">{usd(r.oneTimeCents)}</td>
                  <td className="p-3">{r.weeklyCents ? usd(r.weeklyCents) : "—"}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </main>
    </PublicChrome>
  );
}
