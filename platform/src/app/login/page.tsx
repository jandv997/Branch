"use client";

import { PublicChrome } from "@/components/brand";
import { Button, Card, Input, Label } from "@/components/ui";
import { trpc } from "@/trpc/client";
import { useRouter, useSearchParams } from "next/navigation";
import { Suspense, useState } from "react";

function LoginForm() {
  const router = useRouter();
  const params = useSearchParams();
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [totp, setTotp] = useState("");
  const [err, setErr] = useState<string | null>(null);
  const login = trpc.auth.login.useMutation({
    onSuccess: (d) => {
      if (d.role === "USER") router.push("/app");
      else router.push("/admin");
    },
    onError: (e) => setErr(e.message),
  });
  return (
    <Card className="mx-auto w-full max-w-md p-8">
      <h1 className="text-2xl font-semibold text-white">Sign in</h1>
      <p className="mt-1 text-sm text-slate-500">Patent Pending · software license access</p>
      <div className="mt-6 space-y-4">
        <div>
          <Label>Email</Label>
          <Input value={email} onChange={(e) => setEmail(e.target.value)} />
        </div>
        <div>
          <Label>Password</Label>
          <Input type="password" value={password} onChange={(e) => setPassword(e.target.value)} />
        </div>
        <div>
          <Label>2FA (if enabled)</Label>
          <Input value={totp} onChange={(e) => setTotp(e.target.value)} placeholder="000000" />
        </div>
        {err ? <p className="text-sm text-red-300">{err}</p> : null}
        <Button
          className="w-full"
          onClick={() => login.mutate({ email, password, totp: totp || undefined })}
          disabled={login.isPending}
        >
          {login.isPending ? "Signing in…" : "Sign in"}
        </Button>
        <a href="/forgot" className="block text-center text-xs text-slate-500">
          Forgot password
        </a>
        {params.get("ref") ? <p className="text-xs text-slate-500">Sponsor ref captured at register.</p> : null}
      </div>
    </Card>
  );
}

export default function LoginPage() {
  return (
    <PublicChrome>
      <main className="px-5 py-20">
        <Suspense>
          <LoginForm />
        </Suspense>
      </main>
    </PublicChrome>
  );
}
