import Link from "next/link";
import { PatentBadge, SiteFooter, SiteHeader } from "@/components/brand";
import { EngineVisual } from "@/components/engine-visual";
import { loadConfig } from "@/server/load-config";
import { bpsLabel, usd } from "@/lib/utils";

export default async function LandingPage() {
  const cfg = await loadConfig();
  return (
    <div className="min-h-screen grid-bg">
      <SiteHeader />
      <section className="mx-auto grid max-w-7xl items-center gap-10 px-5 py-16 md:grid-cols-2">
        <div>
          <div className="flex items-center gap-3">
            <span className="text-[10px] uppercase tracking-[0.25em] text-cyan">Q-Core engine · operational</span>
            <PatentBadge />
          </div>
          <h1 className="mt-6 text-4xl font-medium leading-tight md:text-5xl">
            Market-neutral infrastructure.
            <span className="block text-cyan">Software license + 12-month portfolio.</span>
          </h1>
          <p className="mt-5 max-w-xl text-sm leading-relaxed text-slate-400">
            DEX + CEX spot arbitrage sold as a monthly software license. Daily credits are capped (“up to”) at the
            lesser of the engine rate and your license cap. Not a guaranteed return. Trading involves risk of loss.
          </p>
          <div className="mt-8 flex gap-3">
            <Link href="/licenses" className="rounded-md bg-cyan px-4 py-2 text-xs uppercase tracking-widest text-graphite-950">
              View licenses
            </Link>
            <Link href="/compensation" className="rounded-md border border-white/15 px-4 py-2 text-xs uppercase tracking-widest">
              Compensation rules
            </Link>
          </div>
        </div>
        <div className="glass relative overflow-hidden rounded-2xl">
          <EngineVisual />
        </div>
      </section>
      <section className="mx-auto max-w-7xl px-5 pb-20">
        <p className="text-[10px] uppercase tracking-[0.25em] text-slate-500">Licenses — same config the engine uses</p>
        <div className="mt-6 grid gap-4 md:grid-cols-4">
          {cfg.licenses.map((l) => (
            <div key={l.tier} className="glass rounded-xl p-5">
              <div className="flex items-center justify-between">
                <h3 className="text-lg">{l.name}</h3>
                <PatentBadge />
              </div>
              <div className="mt-3 font-mono text-2xl text-cyan">{usd(l.priceCents)}<span className="text-xs text-slate-500"> / 12 mo</span></div>
              <ul className="mt-4 space-y-1 text-xs text-slate-400">
                <li>Cap {usd(l.capCents)}</li>
                <li>Daily credit {bpsLabel(l.dailyCapBps)}</li>
                <li>Min funded {usd(l.minFundCents)}</li>
              </ul>
            </div>
          ))}
        </div>
        <p className="mt-8 max-w-3xl text-xs text-slate-500">{cfg.copy.dailyCapDisclaimer}</p>
      </section>
      <SiteFooter />
    </div>
  );
}
