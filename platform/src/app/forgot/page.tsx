import { SiteFooter, SiteHeader } from "@/components/brand";
import { Card } from "@/components/ui";

export default function ForgotPage() {
  return (
    <div className="min-h-screen">
      <SiteHeader />
      <main className="px-5 py-20">
        <Card className="mx-auto max-w-md">
          <h1 className="text-xl">Forgot password</h1>
          <p className="mt-3 text-sm text-slate-400">
            Password resets are issued by support or finance admin from the GOD profile (superadmin can set a new
            password with reason → audit). Email delivery is not wired in v1.
          </p>
        </Card>
      </main>
      <SiteFooter />
    </div>
  );
}
