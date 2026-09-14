import { PageIntro, PublicChrome } from "@/components/brand";
import { loadConfig } from "@/server/load-config";
import { usdPlain } from "@/lib/utils";

export default async function RanksPage() {
  const cfg = await loadConfig();
  return (
    <PublicChrome>
      <PageIntro
        kicker="Ranks"
        title="Twelve ranks, two meters, one maintenance test"
        body="Rank-up runs on PSV and TV quotas that reset on each advance. Keeping a rank is a separate monthly team-volume test that never touches the rank-up meters."
      />
      <main className="mx-auto max-w-6xl px-5 pb-20">
        <div className="overflow-x-auto border border-white/[0.08] bg-graphite-950 p-6">
          <h2 className="font-display text-lg font-semibold text-[#F8FAFE]">Rank table</h2>
          <p className="mt-1 text-sm text-graphite-500">
            All amounts are direct-deposit volume in USD. Rank salary posts to {cfg.rankPayoutWallet}.
          </p>
          <table className="mt-6 w-full text-left text-sm">
            <thead className="font-mono text-[11px] uppercase tracking-ledger text-graphite-500">
              <tr>
                <th className="pb-3 pr-4">Rank</th>
                <th className="pb-3 pr-4">PSV L1</th>
                <th className="pb-3 pr-4">Rank-up TV L1–L7</th>
                <th className="pb-3 pr-4">Monthly keep TV</th>
                <th className="pb-3 pr-4">One-time</th>
                <th className="pb-3">Weekly</th>
              </tr>
            </thead>
            <tbody>
              {cfg.ranks
                .filter((r) => r.code !== "NONE")
                .map((r) => (
                  <tr key={r.code} className="border-t border-white/[0.08]">
                    <td className="py-3 pr-4 font-medium text-[#F8FAFE]">{r.name}</td>
                    <td className="py-3 pr-4 text-graphite-300">{usdPlain(r.psvCents)}</td>
                    <td className="py-3 pr-4 text-graphite-300">{usdPlain(r.rankUpTvCents)}</td>
                    <td className="py-3 pr-4 text-graphite-300">{usdPlain(r.keepTvCents)}</td>
                    <td className="py-3 pr-4 text-ember">{usdPlain(r.oneTimeCents)}</td>
                    <td className="py-3 text-graphite-300">{r.weeklyCents ? usdPlain(r.weeklyCents) : "—"}</td>
                  </tr>
                ))}
            </tbody>
          </table>
        </div>
        <p className="mt-6 max-w-3xl text-[13px] text-graphite-500">
          Any personal license qualifies all ranks. Rank-up meters consume quota and spill remainder into the next rank.
          Miss 1 month: stop salary, hold 30 days. Miss 2nd: drop one rank. One-time bonus never pays twice for the same
          rank.
        </p>
      </main>
    </PublicChrome>
  );
}
