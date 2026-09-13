import { PublicChrome, PatentBadge } from "@/components/brand";

export default function PrivacyPage() {
  return (
    <PublicChrome>
      <main className="mx-auto max-w-3xl px-5 py-16 text-sm leading-relaxed text-ink-600">
        <PatentBadge />
        <h1 className="mt-4 font-display text-4xl tracking-tight text-ink-900">Privacy</h1>
        <p className="mt-6">
          We process account, KYC, session, and ledger data to operate the license and portfolio software. KYC documents
          are stored in object storage or a local encrypted directory in development. We do not log secrets, TOTP seeds,
          or backup codes.
        </p>
      </main>
    </PublicChrome>
  );
}
