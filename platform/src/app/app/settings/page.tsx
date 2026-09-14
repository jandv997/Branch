import Link from "next/link";
import { Card } from "@/components/ui";

const LINKS = [
  ["Profile", "/app/profile", "Identity and account"],
  ["Security", "/app/security", "2FA, sessions, whitelist"],
  ["KYC", "/app/kyc", "Identity verification gates"],
  ["Notifications", "/app/notifications", "Operational notices"],
  ["Wallets", "/app/wallets", "Ledger balances and export"],
  ["Deposit", "/app/deposit", "Direct deposit instructions"],
  ["Withdraw", "/app/withdraw", "Available-wallet payouts"],
  ["Tickets", "/app/tickets", "Support"],
  ["Legal", "/app/legal", "Disclosures on this account"],
  ["Updates", "/app/updates", "Activity timeline"],
] as const;

export default function SettingsHub() {
  return (
    <div className="space-y-6">
      <div>
        <p className="kicker">Settings</p>
        <h1 className="mt-2 font-display text-3xl font-semibold">Control plane</h1>
        <p className="mt-2 text-sm text-graphite-400">Account, money movement, compliance and notices.</p>
      </div>
      <div className="grid gap-3 md:grid-cols-2">
        {LINKS.map(([label, href, hint]) => (
          <Link key={href} href={href}>
            <Card className="hover:border-ember/40">
              <div className="font-display text-lg">{label}</div>
              <p className="mt-1 text-sm text-graphite-500">{hint}</p>
            </Card>
          </Link>
        ))}
      </div>
    </div>
  );
}
