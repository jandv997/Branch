import { PublicChrome, PatentBadge } from "@/components/brand";

export default function SecurityPage() {
  return (
    <PublicChrome>
      <main className="mx-auto max-w-3xl px-5 py-16">
        <PatentBadge />
        <h1 className="mt-4 font-display text-4xl tracking-tight">Security</h1>
        <ul className="mt-6 space-y-3 text-sm leading-relaxed text-ink-600">
          <li>Email/password sessions in Redis + database, hashed tokens.</li>
          <li>TOTP 2FA + hashed backup codes. 2FA is mandatory before the first withdrawal.</li>
          <li>Whitelist addresses unlock after 24 hours.</li>
          <li>KYC gate above a configurable withdrawal threshold.</li>
          <li>Rate limits and login lockouts. Helmet-style headers. Admin IP allowlist.</li>
          <li>Secrets are never logged. Ledger CSV export redacts 2FA material (it is not stored on ledger rows).</li>
        </ul>
      </main>
    </PublicChrome>
  );
}
