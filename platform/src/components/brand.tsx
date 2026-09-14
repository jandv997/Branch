import Link from "next/link";
import { cn } from "@/lib/utils";
import { SiteNav } from "./site-nav";
import { QMark } from "./mark";
import type { ReactNode } from "react";

export function PatentBadge({ className }: { className?: string }) {
  return (
    <span className={cn("patent-badge font-medium", className)} title="Patent pending — not an issued patent">
      Patent Pending
    </span>
  );
}

export function Wordmark({ compact = false }: { compact?: boolean }) {
  return (
    <Link href="/" className="flex items-center gap-2.5 text-[#F8FAFE]">
      <QMark size={compact ? 28 : 32} />
      <span className="font-display text-[15px] font-semibold tracking-tight">
        QORVEX{compact ? "" : <span className="ml-1.5 font-sans text-[11px] font-medium tracking-ledger text-ember">AI</span>}
      </span>
    </Link>
  );
}

export function SiteHeader() {
  return (
    <header className="sticky top-0 z-40 border-b border-white/[0.06] bg-graphite-950/90 backdrop-blur-md">
      <div className="mx-auto flex max-w-6xl items-center justify-between gap-4 px-5 py-3">
        <Wordmark />
        <SiteNav />
        <div className="flex items-center gap-2">
          <Link href="/login" className="hidden px-3 py-1.5 text-[13px] text-graphite-300 hover:text-white sm:inline">
            Sign in
          </Link>
          <Link
            href="/register"
            className="rounded-sm bg-ember px-3.5 py-1.5 text-[13px] font-medium text-white hover:bg-ember-dim"
          >
            Explore Qorvex
          </Link>
        </div>
      </div>
    </header>
  );
}

export function SiteFooter() {
  return (
    <footer className="border-t border-white/[0.06] px-5 py-14 text-sm text-graphite-400">
      <div className="mx-auto grid max-w-6xl gap-10 md:grid-cols-[1.5fr_1fr_1fr]">
        <div>
          <Wordmark />
          <p className="mt-4 max-w-sm text-[13px] leading-relaxed">
            Market-neutral intelligence for digital markets. Software infrastructure for identifying and routing
            spot-arbitrage opportunities across CEX and DEX venues. Patent Pending.
          </p>
          <div className="mt-4">
            <PatentBadge />
          </div>
        </div>
        <div>
          <div className="kicker mb-3">Product</div>
          <div className="flex flex-col gap-2 text-[13px]">
            <Link href="/technology" className="hover:text-[#F8FAFE]">
              Technology
            </Link>
            <Link href="/licenses" className="hover:text-[#F8FAFE]">
              Licenses
            </Link>
            <Link href="/compensation" className="hover:text-[#F8FAFE]">
              Partner network
            </Link>
            <Link href="/security" className="hover:text-[#F8FAFE]">
              Security
            </Link>
            <Link href="/faq" className="hover:text-[#F8FAFE]">
              FAQ
            </Link>
          </div>
        </div>
        <div>
          <div className="kicker mb-3">Legal</div>
          <div className="flex flex-col gap-2 text-[13px]">
            <Link href="/legal/terms" className="hover:text-[#F8FAFE]">
              Terms
            </Link>
            <Link href="/legal/risk" className="hover:text-[#F8FAFE]">
              Risk disclosure
            </Link>
            <Link href="/legal/privacy" className="hover:text-[#F8FAFE]">
              Privacy
            </Link>
            <Link href="/legal/aml" className="hover:text-[#F8FAFE]">
              AML / KYC
            </Link>
          </div>
        </div>
      </div>
      <div className="mx-auto mt-10 max-w-6xl border-t border-white/[0.06] pt-6 text-[12px] text-graphite-500">
        <p>© 2026 Qorvex AI. Precision. Intelligence. Execution. Patent Pending.</p>
        <p className="mt-2">
          A software license is not an investment product. Daily percentages shown in the application are CAPS,
          expressed as “up to”. They are not guaranteed returns. Historical routing analytics do not guarantee future
          results.
        </p>
      </div>
    </footer>
  );
}

export function BoFooter() {
  return (
    <div className="mt-auto border-t border-white/[0.06] px-6 py-3 font-mono text-[10px] uppercase tracking-ledger text-graphite-500">
      Qorvex AI · Patent Pending · Daily % are caps (“up to”) · Software license, not a guaranteed return
    </div>
  );
}

export function PublicChrome({ children }: { children: ReactNode }) {
  return (
    <div className="theme-public min-h-screen">
      <SiteHeader />
      {children}
      <SiteFooter />
    </div>
  );
}

export function PageIntro({
  kicker,
  title,
  body,
}: {
  kicker: string;
  title: string;
  body: string;
}) {
  return (
    <div className="mx-auto max-w-6xl px-5 pb-10 pt-16">
      <p className="kicker">{kicker}</p>
      <h1 className="mt-3 max-w-3xl font-display text-4xl font-semibold tracking-tight text-[#F8FAFE] md:text-5xl">
        {title}
      </h1>
      <p className="mt-4 max-w-2xl text-[15px] leading-relaxed text-graphite-400">{body}</p>
    </div>
  );
}
