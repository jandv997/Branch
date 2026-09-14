import Link from "next/link";
import { PatentBadge, PublicChrome } from "@/components/brand";
import { LicenseGrid } from "@/components/license-grid";
import { loadConfig } from "@/server/load-config";
import {
  FragmentationViz,
  LiquidityHero,
  OpportunityTable,
  PipelineViz,
  RouterAB,
  VenueNetwork,
} from "@/components/viz";
import { SimBadge } from "@/components/mark";

const STACK = [
  ["Market Data", "Venue adapters normalize books, pools, fees and latency into one tape."],
  ["AI Analysis", "Candidates are scored after costs. Unqualified prints are discarded."],
  ["Opportunity Detection", "Same-asset dislocation across CEX and DEX, never a directional bet."],
  ["Execution Routing", "Paired legs through an abort-on-partial router."],
  ["Risk Controls", "Liquidity, slippage, gas, inventory and venue limits must pass before send."],
  ["Analytics", "Every event writes ledger and audit. Caps are “up to”, not promised yield."],
] as const;

export default async function LandingPage() {
  const cfg = await loadConfig();
  return (
    <PublicChrome>
      <section className="mx-auto grid max-w-6xl items-center gap-10 px-5 pb-8 pt-16 lg:grid-cols-2">
        <div>
          <PatentBadge />
          <h1 className="mt-6 font-display text-4xl font-semibold leading-[1.08] tracking-tight text-[#F8FAFE] md:text-6xl">
            Market-Neutral Intelligence for Digital Markets
          </h1>
          <p className="mt-5 max-w-xl text-[15px] leading-relaxed text-graphite-400">
            Qorvex AI analyzes fragmented liquidity across centralized and decentralized markets to identify
            market-neutral spot-arbitrage opportunities and optimize execution. Sold as a 12-month software license
            with monthly payment options.
          </p>
          <div className="mt-8 flex flex-wrap gap-3">
            <Link href="#fragmentation" className="rounded-sm bg-ember px-4 py-2.5 text-sm font-medium text-white">
              Explore Qorvex
            </Link>
            <Link
              href="/technology"
              className="rounded-sm border border-white/10 px-4 py-2.5 text-sm text-[#F8FAFE] hover:border-ember/40"
            >
              View Technology
            </Link>
          </div>
          <p className="mt-6 max-w-lg text-[12px] leading-relaxed text-graphite-500">
            Not a guaranteed return, pooled fund, or risk-free system. Daily credit percentages in the application are
            CAPS (“up to”).
          </p>
        </div>
        <div className="border border-white/[0.08] bg-graphite-900">
          <div className="flex items-center justify-between border-b border-white/[0.06] px-4 py-2">
            <span className="font-mono text-[10px] uppercase tracking-ledger text-graphite-500">Routing core</span>
            <SimBadge>Illustrative routing — not a live feed</SimBadge>
          </div>
          <LiquidityHero />
        </div>
      </section>

      <section id="fragmentation" className="mx-auto max-w-6xl px-5 py-20">
        <p className="kicker">01 · Market fragmentation</p>
        <h2 className="mt-3 max-w-3xl font-display text-3xl font-semibold text-[#F8FAFE] md:text-4xl">
          Liquidity is split across books, pools and chains.
        </h2>
        <p className="mt-4 max-w-2xl text-sm leading-relaxed text-graphite-400">
          Digital-asset liquidity lives on CEX books, DEX pools, chains, pairs and depth that never sit in one place.
          Qorvex treats that fragmentation as a routing problem, not a directional forecast.
        </p>
        <div className="mt-8">
          <FragmentationViz />
        </div>
      </section>

      <section className="mx-auto max-w-6xl px-5 py-16">
        <p className="kicker">02 · Intelligence layer</p>
        <h2 className="mt-3 font-display text-3xl font-semibold text-[#F8FAFE]">Scan → Analyze → Route → Execute → Verify</h2>
        <p className="mt-3 max-w-2xl text-sm text-graphite-400">
          A deterministic pipeline. Failed legs abort. Credits, if posted, are always min(engine, license cap).
        </p>
        <div className="mt-8">
          <PipelineViz />
        </div>
      </section>

      <section className="mx-auto max-w-6xl px-5 py-16">
        <p className="kicker">03 · Market-neutral architecture</p>
        <h2 className="mt-3 font-display text-3xl font-semibold text-[#F8FAFE]">Two sides. One router. No trend bet.</h2>
        <p className="mt-3 max-w-2xl text-sm text-graphite-400">
          Legs are sized to offset. The position seeks the spread, not the market’s direction. This is a conceptual
          model — not a performance promise.
        </p>
        <div className="mt-8">
          <RouterAB />
        </div>
      </section>

      <section className="mx-auto max-w-6xl px-5 py-16">
        <div className="flex flex-wrap items-end justify-between gap-3">
          <div>
            <p className="kicker">04 · Opportunity engine</p>
            <h2 className="mt-3 font-display text-3xl font-semibold text-[#F8FAFE]">Costed prints, not promised yield.</h2>
          </div>
          <SimBadge />
        </div>
        <p className="mt-3 max-w-2xl text-sm text-graphite-400">
          Spread minus fees, slippage and gas. Net can be zero or negative. Nothing here is a guaranteed opportunity.
        </p>
        <div className="mt-8">
          <OpportunityTable />
        </div>
      </section>

      <section className="mx-auto max-w-6xl px-5 py-16">
        <p className="kicker">05 · Connectivity</p>
        <h2 className="mt-3 font-display text-3xl font-semibold text-[#F8FAFE]">CEX. DEX. Chains. Pools.</h2>
        <p className="mt-3 max-w-2xl text-sm text-graphite-400">
          An abstract venue graph. Exchange logos are not the product. Adapters can be added without rewriting the core.
        </p>
        <div className="mt-8">
          <VenueNetwork />
        </div>
      </section>

      <section className="mx-auto max-w-6xl px-5 py-16">
        <p className="kicker">06 · Technology stack</p>
        <h2 className="mt-3 font-display text-3xl font-semibold text-[#F8FAFE]">Modular services, one ledger.</h2>
        <div className="mt-8 grid gap-px bg-white/[0.08] md:grid-cols-3">
          {STACK.map(([t, b]) => (
            <article key={t} className="bg-graphite-950 p-6">
              <h3 className="font-display text-lg text-[#F8FAFE]">{t}</h3>
              <p className="mt-2 text-sm leading-relaxed text-graphite-400">{b}</p>
            </article>
          ))}
        </div>
      </section>

      <section className="mx-auto max-w-6xl px-5 py-16">
        <p className="kicker">07 · Software licensing</p>
        <h2 className="mt-3 font-display text-3xl font-semibold text-[#F8FAFE]">A 12-month license. Monthly payment options.</h2>
        <p className="mt-3 max-w-2xl text-sm text-graphite-400">
          License fees buy software access. Portfolio funding is separate. Tiers and prices are loaded from the same
          admin-configurable compensation table the engine uses — not hardcoded in the UI.
        </p>
        <div className="mt-8">
          <LicenseGrid licenses={cfg.licenses} />
        </div>
        <p className="mt-6 max-w-3xl text-[13px] text-graphite-500">{cfg.copy.dailyCapDisclaimer}</p>
      </section>
    </PublicChrome>
  );
}
