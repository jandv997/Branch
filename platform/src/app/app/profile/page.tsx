"use client";

import { Button, Card } from "@/components/ui";
import { trpc } from "@/trpc/client";
import { useRouter } from "next/navigation";

export default function ProfilePage() {
  const me = trpc.auth.me.useQuery();
  const logout = trpc.auth.logout.useMutation({ onSuccess: () => router.push("/login") });
  const router = useRouter();
  return (
    <div className="space-y-6">
      <p className="kicker">Settings</p>
      <h1 className="mt-2 font-display text-3xl font-semibold">Profile</h1>
      <Card className="font-mono text-sm">
        <div>{me.data?.email}</div>
        <div className="text-graphite-500">{me.data?.id}</div>
        <div>sponsor {me.data?.sponsorId ?? "none"}</div>
        <Button className="mt-4" variant="outline" onClick={() => logout.mutate()}>
          Logout
        </Button>
      </Card>
    </div>
  );
}
