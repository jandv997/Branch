"use client";

import { trpc } from "@/trpc/client";
import { EmptyState } from "./ui";
import { useState } from "react";

export function FaqList() {
  const q = trpc.public.faqs.useQuery();
  const [open, setOpen] = useState<string | null>(null);
  if (q.isLoading) return <p className="mt-8 text-sm text-slate-500">Loading…</p>;
  const items = (q.data ?? []) as { q: string; a: string }[];
  if (!items.length) return <EmptyState title="No FAQs" body="CMS slug `faq` is empty. Defaults still apply from policy copy." />;
  return (
    <div className="glass divide-y divide-white/10 overflow-hidden rounded-2xl">
      {items.map((f) => {
        const isOpen = open === f.q;
        return (
          <button
            key={f.q}
            type="button"
            className="block w-full px-5 py-4 text-left"
            onClick={() => setOpen(isOpen ? null : f.q)}
          >
            <div className="flex items-start justify-between gap-4">
              <span className="text-[15px] font-medium text-white">{f.q}</span>
              <span className="text-lg text-slate-500">{isOpen ? "–" : "+"}</span>
            </div>
            {isOpen ? <p className="mt-3 text-sm leading-relaxed text-slate-400">{f.a}</p> : null}
          </button>
        );
      })}
    </div>
  );
}
