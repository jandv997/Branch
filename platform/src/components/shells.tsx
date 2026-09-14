"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { BoFooter, PatentBadge, Wordmark } from "./brand";
import { cn } from "@/lib/utils";
import type { ReactNode } from "react";
import { useState } from "react";

const USER_NAV = [
  ["Overview", "/app"],
  ["Intelligence", "/app/intelligence"],
  ["Execution", "/app/execution"],
  ["Portfolio", "/app/portfolios"],
  ["Analytics", "/app/analytics"],
  ["Network", "/app/network"],
  ["Licenses", "/app/licenses"],
  ["Rewards", "/app/rewards"],
  ["Settings", "/app/settings"],
] as const;

const ADMIN_NAV = [
  ["Command", "/admin"],
  ["Users", "/admin/users"],
  ["Ledger", "/admin/ledger"],
  ["Licenses", "/admin/comp"],
  ["Payments", "/admin/withdrawals"],
  ["Deposits", "/admin/deposits"],
  ["Partners", "/admin/fast-start"],
  ["Ranks", "/admin/ranks"],
  ["Analytics", "/admin/analytics"],
  ["Jobs", "/admin/jobs"],
  ["Audit", "/admin/audit"],
  ["CMS", "/admin/cms"],
  ["Tickets", "/admin/tickets"],
  ["Roles", "/admin/roles"],
  ["Updates", "/admin/updates"],
] as const;

export function AppShell({
  children,
  kind,
  impersonating,
}: {
  children: ReactNode;
  kind: "user" | "admin";
  impersonating?: boolean;
}) {
  const pathname = usePathname();
  const nav = kind === "admin" ? ADMIN_NAV : USER_NAV;
  const [open, setOpen] = useState(false);
  const active = (href: string) => pathname === href || (href !== "/app" && href !== "/admin" && pathname.startsWith(href));
  return (
    <div className="theme-desk min-h-screen bg-graphite-950 text-[#F3EFE6]">
      <header className="sticky top-0 z-40 border-b border-white/[0.06] bg-graphite-950/95 backdrop-blur">
        <div className="flex items-center justify-between gap-4 px-4 py-3 lg:px-6">
          <div className="flex items-center gap-4">
            <Wordmark compact />
            <span className="hidden font-mono text-[10px] uppercase tracking-ledger text-ember sm:inline">
              {kind === "admin" ? "Operations" : "Intelligence"}
            </span>
          </div>
          <nav className="hidden items-center gap-1 xl:flex">
            {nav.map(([label, href]) => (
              <Link
                key={href}
                href={href}
                className={cn(
                  "rounded-sm px-2.5 py-1.5 text-[13px] text-graphite-400 hover:text-[#F3EFE6]",
                  active(href) && "bg-white/[0.05] text-[#F3EFE6]",
                )}
              >
                {label}
              </Link>
            ))}
          </nav>
          <div className="flex items-center gap-2">
            <PatentBadge />
            <button type="button" className="rounded-sm border border-white/10 px-2 py-1 text-[12px] xl:hidden" onClick={() => setOpen((v) => !v)}>
              Menu
            </button>
          </div>
        </div>
        {open ? (
          <div className="grid grid-cols-2 gap-1 border-t border-white/[0.06] px-3 py-3 xl:hidden">
            {nav.map(([label, href]) => (
              <Link key={href} href={href} className="px-2 py-2 text-[13px] text-graphite-300" onClick={() => setOpen(false)}>
                {label}
              </Link>
            ))}
          </div>
        ) : null}
      </header>
      {impersonating ? (
        <div className="bg-red-800 px-4 py-2 text-center text-xs font-medium uppercase tracking-ledger text-white">
          View-as impersonation active — all actions are audited
        </div>
      ) : null}
      <div className="flex min-h-[calc(100vh-56px)] flex-col">
        <main className="mx-auto w-full max-w-6xl flex-1 px-4 py-6 lg:px-6">{children}</main>
        <BoFooter />
      </div>
    </div>
  );
}
