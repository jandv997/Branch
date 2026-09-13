"use client";

import { trpc } from "@/trpc/client";
import { EmptyState } from "./ui";

export function FaqList() {
  const q = trpc.public.faqs.useQuery();
  if (q.isLoading) return <p className="mt-8 text-sm text-slate-500">Loading…</p>;
  const items = (q.data ?? []) as { q: string; a: string }[];
  if (!items.length) return <EmptyState title="No FAQs" body="CMS slug `faq` is empty. Defaults still apply from policy copy." />;
  return (
    <div className="mt-8 space-y-4">
      {items.map((f) => (
        <div key={f.q} className="border border-ink-900/12 bg-paper-50 p-5">
          <h2 className="font-display text-xl text-ink-900">{f.q}</h2>
          <p className="mt-2 text-sm text-ink-500">{f.a}</p>
        </div>
      ))}
    </div>
  );
}
