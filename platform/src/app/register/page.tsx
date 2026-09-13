"use client";

import { SiteFooter, SiteHeader } from "@/components/brand";
import { Button, Card, Input, Label } from "@/components/ui";
import { trpc } from "@/trpc/client";
import { useRouter, useSearchParams } from "next/navigation";
import { Suspense, useState } from "react";

function RegisterForm() {
  const router = useRouter();
  const params = useSearchParams();
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [err, setErr] = useState<string | null>(null);
  const mut = trpc.auth.register.useMutation({
    onSuccess: () => router.push("/app"),
    onError: (e) => setErr(e.message),
  });
  return (
    <Card className="mx-auto w-full max-w-md">
      <h1 className="text-xl">Create account</h1>
      <p className="mt-1 text-xs text-slate-500">Passive users can complete license → DD portfolio → credits → withdraw with zero team.</p>
      <div className="mt-6 space-y-4">
        <div>
          <Label>Email</Label>
          <Input value={email} onChange={(e) => setEmail(e.target.value)} />
        </div>
        <div>
          <Label>Password (min 10)</Label>
          <Input type="password" value={password} onChange={(e) => setPassword(e.target.value)} />
        </div>
        {err ? <p className="text-sm text-red-300">{err}</p> : null}
        <Button
          className="w-full"
          disabled={mut.isPending}
          onClick={() =>
            mut.mutate({ email, password, sponsorCode: params.get("ref") ?? undefined })
          }
        >
          Register
        </Button>
      </div>
    </Card>
  );
}

export default function RegisterPage() {
  return (
    <div className="min-h-screen">
      <SiteHeader />
      <main className="px-5 py-20">
        <Suspense>
          <RegisterForm />
        </Suspense>
      </main>
      <SiteFooter />
    </div>
  );
}
