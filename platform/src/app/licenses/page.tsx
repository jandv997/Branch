import { PublicChrome, PatentBadge } from "@/components/brand";
import { loadConfig } from "@/server/load-config";
import { bpsLabel, usd } from "@/lib/utils";
import Link from "next/link";

export default async function LicensesPage() {
  const cfg = await loadConfig();
  return (
    <PublicChrome>
      <main className="mx-auto max-w-6xl px-5 py-16">
        <PatentBadge />
        <h1 className="mt-4 font-display text-4xl tracking-tight md:text-5xl">Software licenses</h1>
        <p className="mt-3 max-w-2xl text-[15px] leading-relaxed text-ink-500">
          License fees provide access to software. Portfolio funding is separate. Daily credits are capped (“up to”).
          Not a guaranteed return.
        </p>
        <div className="mt-10 grid gap-4 md:grid-cols-2">
          {cfg.licenses.map((l) => (
            <article key={l.tier} className="border border-ink-900/12 bg-paper-50 p-6 shadow-stamp">
              <div className="flex items-center justify-between">
                <h2 className="font-display text-3xl">{l.name}</h2>
                <PatentBadge />
              </div>
              <div className="mt-4 font-mono text-3xl text-copper">{usd(l.priceCents)}</div>
              <ul className="mt-4 space-y-2 text-sm text-ink-500">
                <li>Portfolio cap {usd(l.capCents)}</li>
                <li>Daily credit {bpsLabel(l.dailyCapBps)} of principal</li>
                <li>Minimum funded book {usd(l.minFundCents)} + live license</li>
                <li>Cycle {l.cycleDays} days</li>
              </ul>
              <Link href="/register" className="mt-6 inline-block text-[13px] text-copper hover:underline">
                Open account →
              </Link>
            </article>
          ))}
        </div>
        <p className="mt-8 max-w-3xl text-xs text-ink-400">{cfg.copy.dailyCapDisclaimer}</p>
      </main>
    </PublicChrome>
  );
}
