import Link from "next/link";
import { bpsLabel, usd, usdPlain } from "@/lib/utils";
import type { CompConfig } from "@/domain/comp/config";

const BLURBS: Record<string, string> = {
  PULSE: "Entry access to the market-neutral engine and daily settlement.",
  CORE: "Higher portfolio ceiling with expanded venue routing.",
  APEX: "Larger principal cap for accounts that already run a live book.",
  PRIME: "Highest funded ceiling and daily credit cap on the license table.",
};

export function LicenseGrid({
  licenses,
  ctaHref = "/register",
}: {
  licenses: CompConfig["licenses"];
  ctaHref?: string;
}) {
  return (
    <div className="grid gap-4 md:grid-cols-2">
      {licenses.map((l) => (
        <article key={l.tier} className="glass rounded-2xl p-6">
          <div className="flex items-start justify-between gap-3">
            <h3 className="text-xl font-semibold text-white">{l.name}</h3>
            <span className="text-[10px] uppercase tracking-ledger text-slate-500">Patent Pending</span>
          </div>
          <div className="mt-3 text-3xl font-semibold text-white">
            {usdPlain(l.priceCents)}
            <span className="ml-1 text-sm font-normal text-slate-500">/mo license</span>
          </div>
          <dl className="mt-5 space-y-2.5 border-t border-white/10 pt-4 text-sm">
            <div className="flex justify-between gap-4">
              <dt className="text-slate-400">Daily credit cap</dt>
              <dd className="text-cyan">{bpsLabel(l.dailyCapBps)}</dd>
            </div>
            <div className="flex justify-between gap-4">
              <dt className="text-slate-400">Portfolio cap</dt>
              <dd className="text-white">{usdPlain(l.capCents)}</dd>
            </div>
            <div className="flex justify-between gap-4">
              <dt className="text-slate-400">Minimum funded</dt>
              <dd className="text-white">{usdPlain(l.minFundCents)}</dd>
            </div>
            <div className="flex justify-between gap-4">
              <dt className="text-slate-400">Portfolio cycle</dt>
              <dd className="text-white">{Math.round(l.cycleDays / 30.4)} months</dd>
            </div>
          </dl>
          {BLURBS[l.tier] ? <p className="mt-4 text-sm text-slate-400">{BLURBS[l.tier]}</p> : null}
          <Link
            href={ctaHref}
            className="mt-5 block rounded-lg border border-white/10 py-2.5 text-center text-sm text-white hover:border-cyan/40"
          >
            Select {l.name}
          </Link>
        </article>
      ))}
    </div>
  );
}

export function usdCompact(cents: number): string {
  const n = cents / 100;
  if (n >= 1000 && n % 1000 === 0) return `$${n / 1000}K`;
  return usd(cents);
}
