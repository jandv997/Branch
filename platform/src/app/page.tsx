import Link from "next/link";
import { PatentBadge, PublicChrome } from "@/components/brand";
import { LicenseGrid, usdCompact } from "@/components/license-grid";
import { loadConfig } from "@/server/load-config";
import { bpsLabel, usdPlain } from "@/lib/utils";
import { Activity, Layers, Lock, RefreshCw } from "lucide-react";
import type { ReactNode } from "react";

export default async function LandingPage() {
  const cfg = await loadConfig();
  const prime = cfg.licenses.find((l) => l.tier === "PRIME") ?? cfg.licenses.at(-1)!;
  const treeBps = cfg.tree.l1Bps + cfg.tree.l2Bps + cfg.tree.l3Bps;
  const legacy = cfg.ranks.find((r) => r.code === "LEGACY");
  return (
    <PublicChrome>
      <section className="mx-auto max-w-6xl px-5 pb-10 pt-20">
        <PatentBadge />
        <h1 className="mt-6 max-w-4xl text-4xl font-semibold leading-[1.12] tracking-tight text-white md:text-6xl">
          Market-neutral arbitrage infrastructure, <span className="grad-cyan">licensed</span>{" "}
          <span className="grad-violet">monthly.</span>
        </h1>
        <p className="mt-6 max-w-2xl text-[16px] leading-relaxed text-slate-400">
          Qorvex AI is patent-pending software that scans decentralised and centralised spot venues for the same asset
          priced two ways. You hold a monthly license and a 12-month portfolio; the engine settles once per day up to
          your license cap.
        </p>
        <div className="mt-8 flex flex-wrap gap-3">
          <Link
            href="/licenses"
            className="rounded-lg bg-cyan px-4 py-2.5 text-sm font-medium text-navy-950 hover:bg-cyan-dim"
          >
            View licenses →
          </Link>
          <Link
            href="/technology"
            className="rounded-lg border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white hover:border-white/20"
          >
            How the engine works
          </Link>
        </div>
        <div className="mt-12 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
          <HeroStat
            label="Daily credit cap"
            value={bpsLabel(prime.dailyCapBps)}
            hint="Prime license. A cap, never a guaranteed return."
          />
          <HeroStat
            label="Portfolio ceiling"
            value={usdCompact(prime.capCents)}
            hint="Maximum funded principal on Prime."
          />
          <HeroStat
            label="Referral tree"
            value={`${(treeBps / 100).toFixed(2)}%`}
            hint="Three levels, paid once, direct deposits on new portfolios only."
          />
          <HeroStat
            label="Top rank track"
            value={legacy?.name ?? "Legacy"}
            hint={`Weekly salary up to ${legacy ? usdPlain(legacy.weeklyCents) : "$5,000"} while keep volume holds.`}
          />
        </div>
      </section>

      <section className="mx-auto max-w-6xl px-5 py-16">
        <p className="kicker">The engine</p>
        <h2 className="mt-3 text-3xl font-semibold tracking-tight text-white md:text-4xl">
          Four properties that define the infrastructure
        </h2>
        <div className="mt-8 grid gap-4 md:grid-cols-2">
          <Prop
            icon={<RefreshCw className="h-5 w-5 text-cyan" />}
            title="Market-neutral by construction"
            body="Positions are opened and closed inside the same spread window across paired venues. The engine seeks price dislocation, not market direction."
          />
          <Prop
            icon={<Layers className="h-5 w-5 text-cyan" />}
            title="DEX and CEX spot routing"
            body="One order graph over decentralised pools and centralised spot books, with venue health, depth and settlement latency scored continuously."
          />
          <Prop
            icon={<Activity className="h-5 w-5 text-cyan" />}
            title="Daily settlement, capped"
            body="Each active portfolio settles once per day at the lower of the engine rate and your license cap. Inactive license credits zero."
          />
          <Prop
            icon={<Lock className="h-5 w-5 text-cyan" />}
            title="Custodial discipline"
            body="Whitelisted withdrawal addresses with a 24-hour lock, mandatory 2FA before the first withdrawal, and an immutable ledger behind every balance change."
          />
        </div>
      </section>

      <section className="mx-auto max-w-6xl px-5 py-16">
        <div className="flex flex-wrap items-end justify-between gap-4">
          <div>
            <p className="kicker">Licenses</p>
            <h2 className="mt-3 text-3xl font-semibold tracking-tight text-white md:text-4xl">Choose your access tier</h2>
          </div>
          <Link href="/compensation" className="text-sm text-cyan hover:underline">
            Read the full compensation rules →
          </Link>
        </div>
        <div className="mt-8">
          <LicenseGrid licenses={cfg.licenses} />
        </div>
        <p className="mt-6 max-w-4xl text-[13px] leading-relaxed text-slate-500">
          Qorvex AI is sold as a monthly software license. A license is required for any portfolio credit to be issued;
          an inactive license credits zero. Daily percentages are CAPS, expressed as “up to”. They are not guaranteed
          returns. Actual credits depend on engine performance and may be zero.
        </p>
      </section>

      <section className="mx-auto max-w-6xl px-5 pb-20">
        <div className="glass rounded-2xl px-8 py-12 text-center">
          <h2 className="text-3xl font-semibold tracking-tight text-white">A passive license holder never needs a team.</h2>
          <p className="mx-auto mt-4 max-w-2xl text-sm leading-relaxed text-slate-400">
            Buy a license, fund a portfolio by direct deposit, receive daily credits up to your cap, and withdraw weekly.
            Referral, rank and Fast Start mechanics are optional and are documented line by line on the compensation page.
          </p>
          <div className="mt-8 flex flex-wrap justify-center gap-3">
            <Link
              href="/register"
              className="rounded-lg bg-cyan px-4 py-2.5 text-sm font-medium text-navy-950 hover:bg-cyan-dim"
            >
              Create account
            </Link>
            <Link
              href="/legal/risk"
              className="rounded-lg border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-white hover:border-white/20"
            >
              Risk disclosure
            </Link>
          </div>
        </div>
      </section>
    </PublicChrome>
  );
}

function HeroStat({ label, value, hint }: { label: string; value: string; hint: string }) {
  return (
    <div className="glass rounded-2xl p-5">
      <div className="text-[11px] uppercase tracking-ledger text-slate-500">{label}</div>
      <div className="mt-2 text-2xl font-semibold text-white">{value}</div>
      <p className="mt-2 text-[12px] leading-relaxed text-slate-500">{hint}</p>
    </div>
  );
}

function Prop({ icon, title, body }: { icon: ReactNode; title: string; body: string }) {
  return (
    <article className="glass rounded-2xl p-6">
      <div className="mb-4">{icon}</div>
      <h3 className="text-[16px] font-semibold text-white">{title}</h3>
      <p className="mt-2 text-sm leading-relaxed text-slate-400">{body}</p>
    </article>
  );
}
