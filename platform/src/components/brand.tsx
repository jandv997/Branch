import Link from "next/link";
import { cn } from "@/lib/utils";
import { SiteNav } from "./site-nav";
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
    <Link href="/" className="flex items-center gap-2.5 text-white">
      <span className="grid h-8 w-8 place-items-center rounded-lg bg-cyan/15 ring-1 ring-cyan/30">
        <span className="h-3 w-3 rounded-[3px] bg-cyan" aria-hidden />
      </span>
      <span className="text-[15px] font-semibold tracking-tight">
        Qorvex{compact ? "" : <span className="text-cyan"> AI</span>}
      </span>
    </Link>
  );
}

export function SiteHeader() {
  return (
    <header className="sticky top-0 z-40 border-b border-white/5 bg-navy-950/80 backdrop-blur-xl">
      <div className="mx-auto flex max-w-6xl items-center justify-between gap-4 px-5 py-3">
        <Wordmark />
        <SiteNav />
        <div className="flex items-center gap-2">
          <Link href="/login" className="hidden px-3 py-1.5 text-[14px] text-slate-300 hover:text-white sm:inline">
            Sign in
          </Link>
          <Link
            href="/register"
            className="rounded-lg bg-cyan px-3.5 py-1.5 text-[13px] font-medium text-navy-950 hover:bg-cyan-dim"
          >
            Create account
          </Link>
        </div>
      </div>
    </header>
  );
}

export function SiteFooter() {
  return (
    <footer className="border-t border-white/10 px-5 py-14 text-sm text-slate-400">
      <div className="mx-auto grid max-w-6xl gap-10 md:grid-cols-[1.4fr_1fr_1fr]">
        <div>
          <Wordmark />
          <p className="mt-4 max-w-sm text-[13px] leading-relaxed">
            Market-neutral trading infrastructure across decentralised and centralised spot venues, licensed as
            software. Patent Pending.
          </p>
          <div className="mt-4">
            <PatentBadge />
          </div>
        </div>
        <div>
          <div className="kicker mb-3">Product</div>
          <div className="flex flex-col gap-2 text-[13px]">
            <Link href="/technology" className="hover:text-white">
              Technology
            </Link>
            <Link href="/licenses" className="hover:text-white">
              Licenses
            </Link>
            <Link href="/compensation" className="hover:text-white">
              Compensation
            </Link>
            <Link href="/ranks" className="hover:text-white">
              Ranks
            </Link>
            <Link href="/security" className="hover:text-white">
              Security
            </Link>
            <Link href="/faq" className="hover:text-white">
              FAQ
            </Link>
          </div>
        </div>
        <div>
          <div className="kicker mb-3">Legal</div>
          <div className="flex flex-col gap-2 text-[13px]">
            <Link href="/legal/terms" className="hover:text-white">
              Terms
            </Link>
            <Link href="/legal/risk" className="hover:text-white">
              Risk disclosure
            </Link>
            <Link href="/legal/privacy" className="hover:text-white">
              Privacy
            </Link>
            <Link href="/legal/aml" className="hover:text-white">
              AML / KYC
            </Link>
            <Link href="/updates" className="hover:text-white">
              Updates
            </Link>
          </div>
        </div>
      </div>
      <div className="mx-auto mt-10 max-w-6xl border-t border-white/10 pt-6 text-[12px] text-slate-500">
        <p>© 2026 Qorvex AI. All rights reserved. Patent Pending.</p>
        <p className="mt-2">
          Daily percentages are CAPS, expressed as “up to”. They are not guaranteed returns. Actual credits depend on
          engine performance and may be zero.
        </p>
      </div>
    </footer>
  );
}

export function BoFooter() {
  return (
    <div className="mt-auto border-t border-white/10 px-6 py-3 text-[11px] tracking-wide text-slate-500">
      Qorvex AI · Patent Pending · Daily % are caps (“up to”), never guaranteed ROI
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
      <h1 className="mt-3 max-w-3xl text-4xl font-semibold tracking-tight text-white md:text-5xl">{title}</h1>
      <p className="mt-4 max-w-2xl text-[15px] leading-relaxed text-slate-400">{body}</p>
    </div>
  );
}
