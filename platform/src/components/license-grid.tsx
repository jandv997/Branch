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
    <div className="grid gap-px bg-white/[0.08] md:grid-cols-2 xl:grid-cols-4">
      {licenses.map((l) => (
        <article key={l.tier} className="bg-graphite-950 p-6">
          <div className="flex items-start justify-between gap-3">
            <h3 className="font-display text-xl font-semibold text-[#F8FAFE]">{l.name}</h3>
            <span className="font-mono text-[10px] uppercase tracking-ledger text-graphite-500">Patent Pending</span>
          </div>
          <div className="mt-3 font-display text-3xl font-semibold text-[#F8FAFE]">
            {usdPlain(l.priceCents)}
            <span className="ml-1 font-sans text-sm font-normal text-graphite-500">/mo license</span>
          </div>
          <dl className="mt-5 space-y-2.5 border-t border-white/[0.08] pt-4 text-sm">
            <div className="flex justify-between gap-4">
              <dt className="text-graphite-400">Daily credit cap</dt>
              <dd className="text-ember">{bpsLabel(l.dailyCapBps)}</dd>
            </div>
            <div className="flex justify-between gap-4">
              <dt className="text-graphite-400">Portfolio cap</dt>
              <dd className="text-[#F8FAFE]">{usdPlain(l.capCents)}</dd>
            </div>
            <div className="flex justify-between gap-4">
              <dt className="text-graphite-400">Minimum funded</dt>
              <dd className="text-[#F8FAFE]">{usdPlain(l.minFundCents)}</dd>
            </div>
            <div className="flex justify-between gap-4">
              <dt className="text-graphite-400">Portfolio cycle</dt>
              <dd className="text-[#F8FAFE]">{Math.round(l.cycleDays / 30.4)} months</dd>
            </div>
          </dl>
          {BLURBS[l.tier] ? <p className="mt-4 text-sm text-graphite-400">{BLURBS[l.tier]}</p> : null}
          <Link
            href={ctaHref}
            className="mt-5 block rounded-sm border border-white/10 py-2.5 text-center text-sm text-[#F8FAFE] hover:border-ember/40"
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
