import { PublicChrome } from "@/components/brand";
import { Card } from "@/components/ui";

export default function ForgotPage() {
  return (
    <PublicChrome>
      <main className="px-5 py-20">
        <Card className="mx-auto max-w-md p-8">
          <h1 className="font-display text-2xl font-semibold text-[#F8FAFE]">Forgot password</h1>
          <p className="mt-3 text-sm text-graphite-400">
            Password resets are issued by support or finance admin from the GOD profile (superadmin can set a new
            password with reason → audit). Email delivery is not wired in v1.
          </p>
        </Card>
      </main>
    </PublicChrome>
  );
}
