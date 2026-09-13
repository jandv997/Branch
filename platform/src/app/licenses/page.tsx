import { SiteFooter, SiteHeader, PatentBadge } from "@/components/brand";
import { loadConfig } from "@/server/load-config";
import { bpsLabel, usd } from "@/lib/utils";
import Link from "next/link";

export default async function LicensesPage() {
  const cfg = await loadConfig();
  return (
    <div className="min-h-screen">
      <SiteHeader />
      <main className="mx-auto max-w-6xl px-5 py-16">
        <PatentBadge />
        <h1 className="mt-4 text-4xl">Software licenses</h1>
        <p className="mt-3 max-w-2xl text-slate-400">
          License fees provide access to software. Portfolio funding is separate. Daily credits are capped (“up to”).
          Not a guaranteed return.
        </p>
        <div className="mt-10 grid gap-4 md:grid-cols-2">
          {cfg.licenses.map((l) => (
            <article key={l.tier} className="glass rounded-xl p-6">
              <div className="flex items-center justify-between">
                <h2 className="text-2xl">{l.name}</h2>
                <PatentBadge />
              </div>
              <div className="mt-4 font-mono text-3xl text-cyan">{usd(l.priceCents)}</div>
              <ul className="mt-4 space-y-2 text-sm text-slate-400">
                <li>Portfolio cap {usd(l.capCents)}</li>
                <li>Daily credit {bpsLabel(l.dailyCapBps)} of principal</li>
                <li>Minimum funded book {usd(l.minFundCents)} + live license</li>
                <li>Cycle {l.cycleDays} days</li>
              </ul>
              <Link href="/register" className="mt-6 inline-block text-xs uppercase tracking-widest text-cyan">
                Open account →
              </Link>
            </article>
          ))}
        </div>
      </main>
      <SiteFooter />
    </div>
  );
}
