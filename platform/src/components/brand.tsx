import Link from "next/link";
import { cn } from "@/lib/utils";

export function PatentBadge({ className }: { className?: string }) {
  return (
    <span className={cn("patent-badge font-mono", className)} title="Patent pending — not an issued patent">
      Patent Pending
    </span>
  );
}

export function SiteHeader() {
  const links = [
    ["Technology", "/technology"],
    ["Licenses", "/licenses"],
    ["Compensation", "/compensation"],
    ["Ranks", "/ranks"],
    ["Security", "/security"],
    ["FAQ", "/faq"],
  ] as const;
  return (
    <header className="sticky top-0 z-40 border-b border-white/10 bg-graphite-950/80 backdrop-blur-xl">
      <div className="mx-auto flex max-w-7xl items-center justify-between px-5 py-3">
        <Link href="/" className="flex items-center gap-3">
          <span className="grid h-8 w-8 place-items-center rounded-md border border-cyan/40 bg-cyan/10 font-mono text-xs text-cyan">
            QX
          </span>
          <span className="text-sm tracking-widest uppercase text-slate-200">Qorvex AI</span>
        </Link>
        <nav className="hidden items-center gap-6 text-xs uppercase tracking-widest text-slate-400 md:flex">
          {links.map(([l, h]) => (
            <Link key={h} href={h} className="hover:text-cyan">
              {l}
            </Link>
          ))}
        </nav>
        <div className="flex items-center gap-3">
          <PatentBadge />
          <Link href="/login" className="text-xs uppercase tracking-widest text-slate-300 hover:text-white">
            Login
          </Link>
          <Link
            href="/register"
            className="rounded-md bg-cyan px-3 py-1.5 text-xs font-medium uppercase tracking-widest text-graphite-950"
          >
            Register
          </Link>
        </div>
      </div>
    </header>
  );
}

export function SiteFooter() {
  return (
    <footer className="border-t border-white/10 bg-graphite-950 px-5 py-10 text-sm text-slate-400">
      <div className="mx-auto flex max-w-7xl flex-col gap-6 md:flex-row md:justify-between">
        <div>
          <div className="flex items-center gap-3">
            <span className="font-medium text-slate-200">Qorvex AI</span>
            <PatentBadge />
          </div>
          <p className="mt-3 max-w-md text-xs leading-relaxed">
            Software license for market-neutral DEX + CEX spot arbitrage infrastructure. Daily credits are capped
            (“up to”) at the lesser of the engine rate and the license cap. Not a guaranteed return. Trading involves
            risk of loss.
          </p>
        </div>
        <div className="grid grid-cols-2 gap-8 text-xs uppercase tracking-widest">
          <div className="flex flex-col gap-2">
            <Link href="/legal/terms">Terms</Link>
            <Link href="/legal/risk">Risk</Link>
            <Link href="/legal/privacy">Privacy</Link>
            <Link href="/legal/aml">AML</Link>
          </div>
          <div className="flex flex-col gap-2">
            <Link href="/licenses">Licenses</Link>
            <Link href="/compensation">Compensation</Link>
            <Link href="/faq">FAQ</Link>
          </div>
        </div>
      </div>
    </footer>
  );
}

export function BoFooter() {
  return (
    <div className="mt-auto border-t border-white/10 px-6 py-3 text-[10px] uppercase tracking-[0.2em] text-slate-500">
      Qorvex AI · Patent Pending · Daily % are caps (“up to”), never guaranteed ROI
    </div>
  );
}
