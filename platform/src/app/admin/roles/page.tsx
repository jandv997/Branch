import { Card } from "@/components/ui";

export default function RolesPage() {
  return (
    <div className="space-y-4">
      <h1 className="text-2xl">Roles</h1>
      <Card className="text-sm text-slate-400 space-y-2">
        <p><span className="text-white">SUPPORT</span> — tickets, view users, CMS read, jobs view.</p>
        <p><span className="text-white">FINANCE</span> — wallets, withdraws, deposits, ledger, KYC decide, freeze, manual post.</p>
        <p><span className="text-white">SUPERADMIN</span> — everything, including impersonation, config, force rank, sponsor rebuild, 2FA reset. Nothing on a user account is read-only.</p>
      </Card>
    </div>
  );
}
