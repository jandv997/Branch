"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { BoFooter, PatentBadge } from "./brand";
import { cn } from "@/lib/utils";
import type { ReactNode } from "react";

const USER_NAV = [
  ["Overview", "/app"],
  ["Updates", "/app/updates"],
  ["Portfolios", "/app/portfolios"],
  ["Wallets", "/app/wallets"],
  ["Deposit", "/app/deposit"],
  ["Withdraw", "/app/withdraw"],
  ["Team", "/app/team"],
  ["Referrals", "/app/referrals"],
  ["Fast Start", "/app/fast-start"],
  ["Ranks", "/app/ranks"],
  ["Analytics", "/app/analytics"],
  ["Staking", "/app/staking"],
  ["Security", "/app/security"],
  ["KYC", "/app/kyc"],
  ["Tickets", "/app/tickets"],
  ["Profile", "/app/profile"],
  ["Legal", "/app/legal"],
  ["Notifications", "/app/notifications"],
] as const;

const ADMIN_NAV = [
  ["Command", "/admin"],
  ["Updates", "/admin/updates"],
  ["Users", "/admin/users"],
  ["Ledger", "/admin/ledger"],
  ["Withdrawals", "/admin/withdrawals"],
  ["Deposits", "/admin/deposits"],
  ["Comp config", "/admin/comp"],
  ["Rank settlement", "/admin/ranks"],
  ["Fast Start", "/admin/fast-start"],
  ["Analytics", "/admin/analytics"],
  ["CMS", "/admin/cms"],
  ["Jobs", "/admin/jobs"],
  ["Audit", "/admin/audit"],
  ["Tickets", "/admin/tickets"],
  ["Roles", "/admin/roles"],
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
  return (
    <div className="theme-desk grid min-h-screen grid-cols-1 bg-ink-950 text-paper-100 md:grid-cols-[232px_1fr]">
      <aside className="border-r border-paper-100/10 bg-ink-900">
        <div className="flex items-center justify-between border-b border-paper-100/10 px-4 py-4">
          <Link href={kind === "admin" ? "/admin" : "/app"} className="font-display text-lg tracking-tight">
            Qorvex <span className="font-sans text-[10px] uppercase tracking-ledger text-copper">{kind === "admin" ? "Admin" : "Desk"}</span>
          </Link>
          <PatentBadge />
        </div>
        <nav className="flex flex-col px-2 py-3 pb-8">
          {nav.map(([label, href], i) => (
            <Link
              key={href}
              href={href}
              className={cn(
                "flex items-baseline gap-3 px-3 py-2 text-[13px] text-ink-300 hover:bg-ink-800 hover:text-paper-100",
                pathname === href && "bg-ink-800 text-copper",
              )}
            >
              <span className="w-5 font-mono text-[10px] text-ink-400">{String(i + 1).padStart(2, "0")}</span>
              {label}
            </Link>
          ))}
        </nav>
      </aside>
      <div className="flex min-h-screen flex-col">
        {impersonating ? (
          <div className="bg-red-800 px-4 py-2 text-center text-xs font-medium uppercase tracking-ledger text-paper-50">
            View-as impersonation active — all actions are audited
          </div>
        ) : null}
        <main className="ledger-rules flex-1 px-6 py-6">{children}</main>
        <BoFooter />
      </div>
    </div>
  );
}
