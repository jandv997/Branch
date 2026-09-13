import Link from "next/link";
import { PatentBadge, PublicChrome } from "@/components/brand";
import { EngineVisual } from "@/components/engine-visual";
import { loadConfig } from "@/server/load-config";
import { bpsLabel, usd } from "@/lib/utils";

const PIPELINE = [
  ["01", "Detect", "Venue books, pairs, and depth enter the engine as a single tape."],
  ["02", "Analyze", "Same-asset prices, fees, and inventory are compared across CEX and DEX."],
  ["03", "Evaluate", "Each candidate is scored against risk limits before it can qualify."],
  ["04", "Execute", "Qualifying spot routes run to programmed logic — not discretionary trading."],
  ["05", "Monitor", "Fills, residual inventory, and posted credits are reconciled to the ledger."],
] as const;

const STRATEGIES = [
  ["01", "Cross-exchange spot", "The same asset, two centralized books. Buy the cheap print, sell the rich print.", "CEX A → asset → CEX B"],
  ["02", "DEX spot", "AMMs and on-venue books checked for the same pair after gas and slippage.", "DEX A → chain → DEX B"],
  ["03", "Cross-market spot", "CEX versus DEX on one asset when the all-in spread still clears costs.", "CEX book ↔ DEX pool"],
  ["04", "Statistical spot", "Mean-reversion and pair residuals used only as a filter, never as a promised yield.", "Residual → threshold → skip or route"],
] as const;

export default async function LandingPage() {
  const cfg = await loadConfig();
  return (
    <PublicChrome>
      <section className="mx-auto grid max-w-6xl items-center gap-12 px-5 py-16 lg:grid-cols-[1.05fr_0.95fr]">
        <div>
          <div className="flex flex-wrap items-center gap-3">
            <span className="inline-flex items-center gap-2 font-mono text-[11px] uppercase tracking-ledger text-copper">
              <span className="h-1.5 w-1.5 bg-copper" />
              Engine operational
            </span>
            <PatentBadge />
          </div>
          <h1 className="mt-6 font-display text-4xl font-medium leading-[1.12] tracking-tight text-ink-900 md:text-6xl">
            A desk for
            <span className="italic text-copper"> market-neutral spot.</span>
          </h1>
          <p className="mt-5 max-w-xl text-[15px] leading-relaxed text-ink-500">
            One software license. A twelve-month portfolio. DEX and CEX spot routes run as infrastructure — daily
            credits are capped (“up to”) at the lesser of the engine rate and your license cap. Not a guaranteed
            return.
          </p>
          <div className="mt-8 flex flex-wrap gap-3">
            <Link href="/licenses" className="bg-copper px-4 py-2.5 text-[13px] font-medium text-paper-50 hover:bg-copper-dim">
              View licenses
            </Link>
            <Link href="/technology" className="border border-ink-900/20 px-4 py-2.5 text-[13px] hover:border-copper hover:text-copper">
              Read the pipeline
            </Link>
          </div>
          <ul className="mt-8 space-y-2 text-[13px] text-ink-600">
            <li>— License fee buys software access, not a yield product.</li>
            <li>— Portfolio funding is separate. Credits post to the earnings wallet.</li>
            <li>— Payouts are computed in the backend policy module. The UI never invents them.</li>
          </ul>
          <dl className="mt-10 grid grid-cols-3 gap-4 border-t border-ink-900/10 pt-6">
            <div>
              <dt className="font-mono text-[10px] uppercase tracking-ledger text-ink-400">Licenses</dt>
              <dd className="mt-1 font-display text-3xl text-ink-900">{cfg.licenses.length}</dd>
            </div>
            <div>
              <dt className="font-mono text-[10px] uppercase tracking-ledger text-ink-400">Portfolio</dt>
              <dd className="mt-1 font-display text-3xl text-ink-900">12 mo</dd>
            </div>
            <div>
              <dt className="font-mono text-[10px] uppercase tracking-ledger text-ink-400">Tree</dt>
              <dd className="mt-1 font-display text-3xl text-ink-900">3 lvl</dd>
            </div>
          </dl>
        </div>
        <EngineVisual />
      </section>

      <section className="border-y border-ink-900/10 bg-paper-50/70">
        <div className="mx-auto max-w-6xl px-5 py-16">
          <p className="kicker">Inside the engine</p>
          <h2 className="mt-3 font-display text-3xl tracking-tight md:text-4xl">A system, operating continuously.</h2>
          <p className="mt-3 max-w-2xl text-sm leading-relaxed text-ink-500">
            From detection to monitoring, every qualifying spot route follows the same five-step desk. This is
            software infrastructure — not a promise of profit.
          </p>
          <div className="mt-10 grid gap-px bg-ink-900/10 md:grid-cols-5">
            {PIPELINE.map(([n, title, body]) => (
              <article key={n} className="bg-paper-50 p-5">
                <div className="kicker">{n}</div>
                <h3 className="mt-3 font-display text-xl">{title}</h3>
                <p className="mt-2 text-[13px] leading-relaxed text-ink-500">{body}</p>
              </article>
            ))}
          </div>
        </div>
      </section>

      <section className="mx-auto max-w-6xl px-5 py-16">
        <p className="kicker">Spot strategies</p>
        <h2 className="mt-3 font-display text-3xl tracking-tight md:text-4xl">One engine. Four kinds of print.</h2>
        <p className="mt-3 max-w-2xl text-sm leading-relaxed text-ink-500">
          Qorvex is built for market-neutral spot. We do not describe this as guaranteed, risk-free, or riskless arb.
        </p>
        <div className="mt-10 grid gap-4 md:grid-cols-2">
          {STRATEGIES.map(([n, title, body, code]) => (
            <article key={n} className="border border-ink-900/12 bg-paper-50 p-6 shadow-stamp">
              <div className="kicker">{n}</div>
              <h3 className="mt-3 font-display text-2xl">{title}</h3>
              <p className="mt-3 text-sm leading-relaxed text-ink-500">{body}</p>
              <pre className="mt-4 border-t border-ink-900/10 pt-3 font-mono text-[11px] text-copper">{code}</pre>
            </article>
          ))}
        </div>
      </section>

      <section className="border-t border-ink-900/10 bg-ink-900 text-paper-100">
        <div className="mx-auto max-w-6xl px-5 py-16">
          <p className="kicker">Licenses — same config the engine uses</p>
          <h2 className="mt-3 font-display text-3xl tracking-tight">Four desks. One ledger.</h2>
          <div className="mt-10 grid gap-4 md:grid-cols-4">
            {cfg.licenses.map((l) => (
              <article key={l.tier} className="border border-paper-100/15 p-5">
                <div className="flex items-start justify-between gap-2">
                  <h3 className="font-display text-xl">{l.name}</h3>
                  <PatentBadge />
                </div>
                <div className="mt-4 font-mono text-2xl text-copper">
                  {usd(l.priceCents)}
                  <span className="text-[11px] text-ink-300"> / 12 mo</span>
                </div>
                <ul className="mt-4 space-y-1 text-[12px] text-ink-200">
                  <li>Cap {usd(l.capCents)}</li>
                  <li>Daily credit {bpsLabel(l.dailyCapBps)}</li>
                  <li>Min funded {usd(l.minFundCents)}</li>
                </ul>
              </article>
            ))}
          </div>
          <p className="mt-8 max-w-3xl text-xs leading-relaxed text-ink-300">{cfg.copy.dailyCapDisclaimer}</p>
          <Link href="/licenses" className="mt-6 inline-block text-[13px] text-copper hover:underline">
            Full license table →
          </Link>
        </div>
      </section>
    </PublicChrome>
  );
}
