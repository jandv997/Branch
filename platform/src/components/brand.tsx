import Link from "next/link";
import { cn } from "@/lib/utils";
import { SiteNav } from "./site-nav";
import type { ReactNode } from "react";

export function PatentBadge({ className }: { className?: string }) {
  return (
    <span className={cn("patent-badge font-mono", className)} title="Patent pending — not an issued patent">
      Patent Pending
    </span>
  );
}

export function Wordmark({ compact = false }: { compact?: boolean }) {
  return (
    <Link href="/" className="flex items-center gap-3 text-ink-900">
      <span className="h-8 w-[3px] bg-copper" aria-hidden />
      <span className="leading-none">
        <span className="font-display text-xl tracking-tight">Qorvex</span>
        {!compact ? (
          <span className="ml-2 font-mono text-[10px] uppercase tracking-ledger text-copper">AI</span>
        ) : null}
      </span>
    </Link>
  );
}

export function SiteHeader() {
  return (
    <header className="sticky top-0 z-40 border-b border-ink-900/10 bg-paper-50/90 backdrop-blur-sm">
      <div className="mx-auto flex max-w-6xl items-center justify-between px-5 py-3">
        <Wordmark />
        <SiteNav />
        <div className="flex items-center gap-3">
          <PatentBadge className="hidden sm:inline-block" />
          <Link href="/login" className="text-[13px] text-ink-700 hover:text-copper">
            Login
          </Link>
          <Link
            href="/register"
            className="border border-copper bg-copper px-3 py-1.5 text-[12px] font-medium text-paper-50 hover:bg-copper-dim"
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
    <footer className="border-t border-ink-900/10 bg-paper-50 px-5 py-12 text-sm text-ink-500">
      <div className="mx-auto flex max-w-6xl flex-col gap-8 md:flex-row md:justify-between">
        <div>
          <div className="flex items-center gap-3">
            <span className="font-display text-lg text-ink-900">Qorvex AI</span>
            <PatentBadge />
          </div>
          <p className="mt-4 max-w-md text-xs leading-relaxed">
            Software license for market-neutral DEX + CEX spot arbitrage infrastructure. Daily credits are capped
            (“up to”) at the lesser of the engine rate and the license cap. Not a guaranteed return. Trading involves
            risk of loss.
          </p>
        </div>
        <div className="grid grid-cols-2 gap-10 text-[12px]">
          <div className="flex flex-col gap-2">
            <Link href="/legal/terms" className="hover:text-copper">
              Terms
            </Link>
            <Link href="/legal/risk" className="hover:text-copper">
              Risk
            </Link>
            <Link href="/legal/privacy" className="hover:text-copper">
              Privacy
            </Link>
            <Link href="/legal/aml" className="hover:text-copper">
              AML
            </Link>
          </div>
          <div className="flex flex-col gap-2">
            <Link href="/licenses" className="hover:text-copper">
              Licenses
            </Link>
            <Link href="/compensation" className="hover:text-copper">
              Compensation
            </Link>
            <Link href="/technology" className="hover:text-copper">
              Technology
            </Link>
            <Link href="/faq" className="hover:text-copper">
              FAQ
            </Link>
          </div>
        </div>
      </div>
    </footer>
  );
}

export function BoFooter() {
  return (
    <div className="mt-auto border-t border-paper-100/10 px-6 py-3 font-mono text-[10px] uppercase tracking-ledger text-ink-400">
      Qorvex AI · Patent Pending · Daily % are caps (“up to”), never guaranteed ROI
    </div>
  );
}

export function PublicChrome({ children }: { children: ReactNode }) {
  return (
    <div className="theme-public min-h-screen">
      <div className="bg-ink-900 text-paper-100">
        <div className="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-2 px-5 py-2 font-mono text-[10px] uppercase tracking-ledger">
          <span>Patent pending software · daily credits are caps (“up to”)</span>
          <span className="hidden sm:inline">Not a guaranteed return · trading involves risk of loss</span>
        </div>
      </div>
      <SiteHeader />
      {children}
      <SiteFooter />
    </div>
  );
}
