"use client";

import { Button, Card } from "@/components/ui";
import { trpc } from "@/trpc/client";

export default function LegalAcceptsPage() {
  const q = trpc.user.legalAccepts.useQuery();
  const acc = trpc.user.acceptLegal.useMutation({ onSuccess: () => q.refetch() });
  return (
    <div className="space-y-6">
      <h1 className="text-2xl">Legal accepts</h1>
      {["terms", "risk", "privacy", "aml"].map((doc) => (
        <Card key={doc} className="flex items-center justify-between">
          <span className="uppercase tracking-widest text-xs">{doc}</span>
          <Button onClick={() => acc.mutate({ doc, version: "1.0" })}>Accept v1.0</Button>
        </Card>
      ))}
      <pre className="text-[10px] text-slate-500">{JSON.stringify(q.data, null, 2)}</pre>
    </div>
  );
}
