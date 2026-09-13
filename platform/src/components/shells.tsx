"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { BoFooter, PatentBadge, Wordmark } from "./brand";
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
    <div className="theme-desk grid min-h-screen grid-cols-1 bg-navy-950 text-slate-100 md:grid-cols-[232px_1fr]">
      <aside className="border-r border-white/10 bg-navy-900">
        <div className="flex items-center justify-between gap-2 border-b border-white/10 px-4 py-4">
          <Wordmark compact />
          <PatentBadge />
        </div>
        <p className="px-4 pt-4 text-[10px] uppercase tracking-ledger text-cyan">
          {kind === "admin" ? "Admin" : "Account"}
        </p>
        <nav className="flex flex-col px-2 py-3 pb-8">
          {nav.map(([label, href]) => (
            <Link
              key={href}
              href={href}
              className={cn(
                "rounded-lg px-3 py-2 text-[13px] text-slate-400 hover:bg-white/5 hover:text-white",
                pathname === href && "bg-white/5 text-cyan",
              )}
            >
              {label}
            </Link>
          ))}
        </nav>
      </aside>
      <div className="flex min-h-screen flex-col">
        {impersonating ? (
          <div className="bg-red-700 px-4 py-2 text-center text-xs font-medium uppercase tracking-ledger text-white">
            View-as impersonation active — all actions are audited
          </div>
        ) : null}
        <main className="flex-1 px-6 py-6">{children}</main>
        <BoFooter />
      </div>
    </div>
  );
}
