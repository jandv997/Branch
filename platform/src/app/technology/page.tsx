import { PageIntro, PublicChrome } from "@/components/brand";
import { PipelineViz, RouterAB } from "@/components/viz";

const STEPS = [
  [
    "01",
    "Market data",
    "Order books, pool reserves, fee schedules, withdrawal states and settlement latency are polled from connected CEX and DEX venues.",
  ],
  [
    "02",
    "AI analysis",
    "Candidate pairs are scored on realisable spread after fees, slippage and transfer cost. Anything that cannot close inside the window is discarded before sizing.",
  ],
  [
    "03",
    "Opportunity detection",
    "Same-asset dislocation across venues — never a directional bet. Legs are sized so long and short offset.",
  ],
  [
    "04",
    "Execution routing",
    "Legs are submitted together with abort logic on partial fill, venue degradation or spread collapse. Failed legs unwind rather than hold.",
  ],
  [
    "05",
    "Risk controls",
    "Liquidity, slippage, gas, inventory, venue limits and strategy limits must pass. A failed check is not sent.",
  ],
  [
    "06",
    "Analytics",
    "Every event writes ledger and audit. Daily credits, if posted, are min(engine, license cap). Caps are “up to”, not promised yield.",
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
      <main className="mx-auto max-w-6xl space-y-10 px-5 pb-20">
        <PipelineViz />
        <RouterAB />
        <div className="grid gap-px bg-white/[0.08] md:grid-cols-2">
          {STEPS.map(([n, title, body]) => (
            <article key={n} className="bg-graphite-950 p-6">
              <div className="font-mono text-sm text-ember">{n}</div>
              <h2 className="mt-2 font-display text-lg font-semibold text-[#F3EFE6]">{title}</h2>
              <p className="mt-2 text-sm leading-relaxed text-graphite-400">{body}</p>
            </article>
          ))}
        </div>
      </main>
    </PublicChrome>
  );
}
