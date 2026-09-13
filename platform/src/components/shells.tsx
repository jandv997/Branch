"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { BoFooter, PatentBadge } from "./brand";
import { cn } from "@/lib/utils";
import type { ReactNode } from "react";

const USER_NAV = [
  ["Overview", "/app"],
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
  ["Updates", "/app/updates"],
] as const;

const ADMIN_NAV = [
  ["Command", "/admin"],
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
  ["Updates", "/updates"],
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
    <div className="grid min-h-screen grid-cols-1 md:grid-cols-[240px_1fr] bg-graphite-950">
      <aside className="border-r border-white/10 bg-graphite-900/70">
        <div className="flex items-center justify-between px-4 py-4">
          <Link href={kind === "admin" ? "/admin" : "/app"} className="text-xs uppercase tracking-[0.25em]">
            Qorvex {kind === "admin" ? "Admin" : "BO"}
          </Link>
          <PatentBadge />
        </div>
        <nav className="flex flex-col px-2 pb-8">
          {nav.map(([label, href]) => (
            <Link
              key={href}
              href={href}
              className={cn(
                "rounded-md px-3 py-2 text-xs uppercase tracking-widest text-slate-400 hover:bg-white/5 hover:text-cyan",
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
          <div className="bg-red-600 px-4 py-2 text-center text-xs font-medium uppercase tracking-widest text-white">
            View-as impersonation active — all actions are audited
          </div>
        ) : null}
        <main className="grid-bg flex-1 px-6 py-6">{children}</main>
        <BoFooter />
      </div>
    </div>
  );
}
