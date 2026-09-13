"use client";

import Link from "next/link";
import { useState } from "react";
import { cn } from "@/lib/utils";

const PRIMARY = [
  ["Technology", "/technology"],
  ["Licenses", "/licenses"],
  ["Compensation", "/compensation"],
  ["Updates", "/updates"],
] as const;

const MORE = [
  ["Ranks", "/ranks"],
  ["Security", "/security"],
  ["FAQ", "/faq"],
  ["Terms", "/legal/terms"],
  ["Risk", "/legal/risk"],
] as const;

export function SiteNav() {
  const [open, setOpen] = useState(false);
  return (
    <nav className="flex items-center gap-1 text-[13px] text-ink-600">
      <div className="hidden items-center md:flex">
        {PRIMARY.map(([label, href]) => (
          <Link key={href} href={href} className="px-3 py-1 hover:text-copper">
            {label}
          </Link>
        ))}
        <div className="relative">
          <button
            type="button"
            className="px-3 py-1 hover:text-copper"
            aria-expanded={open}
            onClick={() => setOpen((v) => !v)}
          >
            More
          </button>
          {open ? (
            <div className="absolute right-0 top-full z-50 mt-2 min-w-40 border border-ink-900/15 bg-paper-50 py-2 shadow-stamp">
              {MORE.map(([label, href]) => (
                <Link
                  key={href}
                  href={href}
                  className="block px-4 py-1.5 text-ink-700 hover:bg-paper-200 hover:text-copper"
                  onClick={() => setOpen(false)}
                >
                  {label}
                </Link>
              ))}
            </div>
          ) : null}
        </div>
      </div>
      <details className="md:hidden">
        <summary className={cn("cursor-pointer list-none px-2 py-1")}>Menu</summary>
        <div className="absolute right-5 top-16 z-50 min-w-48 border border-ink-900/15 bg-paper-50 py-2 shadow-stamp">
          {[...PRIMARY, ...MORE].map(([label, href]) => (
            <Link key={href} href={href} className="block px-4 py-1.5 hover:text-copper">
              {label}
            </Link>
          ))}
        </div>
      </details>
    </nav>
  );
}
