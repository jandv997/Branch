"use client";

import { AppShell } from "@/components/shells";
import { trpc } from "@/trpc/client";
import { useRouter } from "next/navigation";
import { useEffect, type ReactNode } from "react";

export function UserBoLayout({ children }: { children: ReactNode }) {
  const router = useRouter();
  const me = trpc.auth.me.useQuery();
  useEffect(() => {
    if (me.isFetched && !me.data) router.push("/login");
  }, [me.isFetched, me.data, router]);
  if (!me.data) return <div className="theme-desk min-h-screen bg-ink-950 p-10 text-sm text-ink-300">Authenticating…</div>;
  return <AppShell kind="user" impersonating={Boolean(me.data.impersonatedBy)}>{children}</AppShell>;
}

export function AdminBoLayout({ children }: { children: ReactNode }) {
  const router = useRouter();
  const me = trpc.auth.me.useQuery();
  useEffect(() => {
    if (me.isFetched && (!me.data || me.data.role === "USER")) router.push("/login");
  }, [me.isFetched, me.data, router]);
  if (!me.data || me.data.role === "USER") return <div className="theme-desk min-h-screen bg-ink-950 p-10 text-sm text-ink-300">Authenticating…</div>;
  return <AppShell kind="admin" impersonating={Boolean(me.data.impersonatedBy)}>{children}</AppShell>;
}
