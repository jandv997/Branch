import { PageIntro, PublicChrome } from "@/components/brand";

const STEPS = [
  [
    "01",
    "Venue ingest",
    "Order books, pool reserves, fee schedules, withdrawal states and settlement latency are polled continuously from every connected decentralised and centralised spot venue.",
  ],
  [
    "02",
    "Dislocation scoring",
    "Candidate pairs are scored on realisable spread after fees, slippage and transfer cost. Anything that cannot close inside the spread window is discarded before sizing.",
  ],
  [
    "03",
    "Neutral sizing",
    "Each candidate is sized so the long and short legs offset. Net directional exposure is targeted at zero — the position seeks the spread, not the trend.",
  ],
  [
    "04",
    "Paired execution",
    "Legs are submitted together with abort logic on partial fill, venue degradation or spread collapse. Failed legs are unwound rather than held.",
  ],
  [
    "05",
    "Daily settlement",
    "The engine rate for the period is applied to each active portfolio at the lower of engine rate and license cap. An inactive license credits zero for that day.",
  ],
] as const;

export default function TechnologyPage() {
  return (
    <PublicChrome>
      <PageIntro
        kicker="Technology"
        title="A single order graph over fragmented spot liquidity"
        body="Qorvex AI is patent-pending infrastructure. It treats decentralised pools and centralised spot books as one venue set and only acts when the same asset is priced two ways by enough to clear all costs."
      />
      <main className="mx-auto max-w-6xl space-y-3 px-5 pb-20">
        {STEPS.map(([n, title, body]) => (
          <article key={n} className="glass flex gap-5 rounded-2xl p-6">
            <div className="font-mono text-sm text-cyan">{n}</div>
            <div>
              <h2 className="text-lg font-semibold text-white">{title}</h2>
              <p className="mt-2 text-sm leading-relaxed text-slate-400">{body}</p>
            </div>
          </article>
        ))}
      </main>
    </PublicChrome>
  );
}
