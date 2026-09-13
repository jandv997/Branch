import { SiteFooter, SiteHeader, PatentBadge } from "@/components/brand";
import { loadConfig } from "@/server/load-config";
import { usd } from "@/lib/utils";

export default async function RanksPage() {
  const cfg = await loadConfig();
  return (
    <div className="min-h-screen">
      <SiteHeader />
      <main className="mx-auto max-w-6xl px-5 py-16">
        <PatentBadge />
        <h1 className="mt-4 text-4xl">Ranks</h1>
        <p className="mt-3 text-slate-400">
          Any personal license qualifies all ranks. Rank-up meters consume quota and spill remainder into the next rank.
          Monthly keep TV is a separate meter. Miss 1 month: stop salary, hold 30 days. Miss 2nd: drop one rank. One-time
          bonus never pays twice for the same rank. Rank salary posts to {cfg.rankPayoutWallet}.
        </p>
        <div className="mt-8 overflow-x-auto">
          <table className="w-full text-left text-xs">
            <thead className="uppercase tracking-widest text-slate-500">
              <tr>
                <th className="p-2">Rank</th>
                <th className="p-2">PSV L1</th>
                <th className="p-2">Rank-up TV L1–L7</th>
                <th className="p-2">Monthly keep TV</th>
                <th className="p-2">One-time</th>
                <th className="p-2">Weekly</th>
              </tr>
            </thead>
            <tbody>
              {cfg.ranks.filter((r) => r.code !== "NONE").map((r) => (
                <tr key={r.code} className="border-t border-white/10 font-mono">
                  <td className="p-2 text-slate-200">{r.name}</td>
                  <td className="p-2">{usd(r.psvCents)}</td>
                  <td className="p-2">{usd(r.rankUpTvCents)}</td>
                  <td className="p-2">{usd(r.keepTvCents)}</td>
                  <td className="p-2 text-cyan">{usd(r.oneTimeCents)}</td>
                  <td className="p-2">{r.weeklyCents ? usd(r.weeklyCents) : "—"}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </main>
      <SiteFooter />
    </div>
  );
}
