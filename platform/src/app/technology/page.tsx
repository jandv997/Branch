import { PublicChrome, PatentBadge } from "@/components/brand";

const PIPELINE = [
  ["01", "Detect", "Market data streams enter as a single tape across CEX and DEX spot venues."],
  ["02", "Analyze", "Pricing, liquidity, fees, and inventory are compared on the same asset."],
  ["03", "Evaluate", "Candidates are scored against risk parameters. Unqualified prints are skipped."],
  ["04", "Execute", "Qualifying spot routes run to programmed logic."],
  ["05", "Monitor", "Fills and posted credits are reconciled. Credits are always min(engine, license cap)."],
] as const;

export default function TechnologyPage() {
  return (
    <PublicChrome>
      <main className="mx-auto max-w-6xl px-5 py-16">
        <PatentBadge />
        <h1 className="mt-4 font-display text-4xl tracking-tight md:text-5xl">The desk, written as software.</h1>
        <p className="mt-4 max-w-2xl text-[15px] leading-relaxed text-ink-500">
          Qorvex routes a deterministic pipeline across CEX and DEX spot venues. The engine rate is an admin-set
          default in v1. Credits posted to user portfolios are always min(engine rate, license daily cap) and are
          expressed as “up to”. Not a guaranteed return.
        </p>
        <div className="mt-12 grid gap-px bg-ink-900/10 md:grid-cols-5">
          {PIPELINE.map(([n, title, body]) => (
            <article key={n} className="bg-paper-50 p-5">
              <div className="kicker">{n}</div>
              <h2 className="mt-3 font-display text-2xl">{title}</h2>
              <p className="mt-2 text-sm leading-relaxed text-ink-500">{body}</p>
            </article>
          ))}
        </div>
        <section className="mt-16 grid gap-8 md:grid-cols-2">
          <div className="border border-ink-900/12 bg-paper-50 p-6">
            <p className="kicker">What this is</p>
            <p className="mt-3 text-sm leading-relaxed text-ink-600">
              A monthly software license plus a twelve-month portfolio. Payouts are computed in the backend policy
              module. The UI never invents them.
            </p>
          </div>
          <div className="border border-ink-900/12 bg-paper-50 p-6">
            <p className="kicker">What this is not</p>
            <p className="mt-3 text-sm leading-relaxed text-ink-600">
              Not a guaranteed ROI, not risk-free, not riskless arb. Trading involves risk of loss. Daily percentages
              shown are caps.
            </p>
          </div>
        </section>
      </main>
    </PublicChrome>
  );
}
