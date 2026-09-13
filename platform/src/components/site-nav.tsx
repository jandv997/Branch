"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { useState } from "react";
import { cn } from "@/lib/utils";

export const PRIMARY_NAV = [
  ["Technology", "/technology"],
  ["Licenses", "/licenses"],
  ["Compensation", "/compensation"],
  ["Ranks", "/ranks"],
  ["Security", "/security"],
  ["FAQ", "/faq"],
] as const;

export function SiteNav() {
  const pathname = usePathname();
  const [open, setOpen] = useState(false);
  return (
    <nav className="flex items-center text-[14px] text-slate-300">
      <div className="hidden items-center md:flex">
        {PRIMARY_NAV.map(([label, href]) => (
          <Link
            key={href}
            href={href}
            className={cn(
              "rounded-lg px-3 py-1.5 hover:text-white",
              pathname === href && "bg-white/5 text-white",
            )}
          >
            {label}
          </Link>
        ))}
      </div>
      <div className="relative md:hidden">
        <button type="button" className="rounded-lg px-3 py-1.5" onClick={() => setOpen((v) => !v)}>
          Menu
        </button>
        {open ? (
          <div className="absolute right-0 top-full z-50 mt-2 min-w-44 rounded-xl border border-white/10 bg-navy-900 py-2">
            {PRIMARY_NAV.map(([label, href]) => (
              <Link
                key={href}
                href={href}
                className="block px-4 py-2 hover:bg-white/5 hover:text-white"
                onClick={() => setOpen(false)}
              >
                {label}
              </Link>
            ))}
          </div>
        ) : null}
      </div>
    </nav>
  );
}
