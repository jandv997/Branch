"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { useState } from "react";
import { cn } from "@/lib/utils";

export const PRIMARY_NAV = [
  ["Technology", "/technology"],
  ["Licenses", "/licenses"],
  ["Network", "/compensation"],
  ["Security", "/security"],
  ["FAQ", "/faq"],
] as const;

export function SiteNav() {
  const pathname = usePathname();
  const [open, setOpen] = useState(false);
  return (
    <nav className="flex items-center text-[13px] text-graphite-300">
      <div className="hidden items-center md:flex">
        {PRIMARY_NAV.map(([label, href]) => (
          <Link
            key={href}
            href={href}
            className={cn(
              "rounded-sm px-3 py-1.5 hover:text-[#F8FAFE]",
              pathname === href && "bg-white/[0.04] text-[#F8FAFE]",
            )}
          >
            {label}
          </Link>
        ))}
      </div>
      <div className="relative md:hidden">
        <button type="button" className="rounded-sm px-3 py-1.5" onClick={() => setOpen((v) => !v)}>
          Menu
        </button>
        {open ? (
          <div className="absolute right-0 top-full z-50 mt-2 min-w-44 border border-white/10 bg-graphite-900 py-2">
            {PRIMARY_NAV.map(([label, href]) => (
              <Link
                key={href}
                href={href}
                className="block px-4 py-2 hover:bg-white/5 hover:text-[#F8FAFE]"
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
