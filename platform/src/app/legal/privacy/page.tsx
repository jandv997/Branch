import { PublicChrome, PatentBadge } from "@/components/brand";

export default function PrivacyPage() {
  return (
    <PublicChrome>
      <main className="mx-auto max-w-3xl px-5 py-16 text-sm leading-relaxed text-slate-400">
        <PatentBadge />
        <h1 className="font-display text-4xl font-semibold tracking-tight text-[#F3EFE6]">Privacy</h1>
        <p className="mt-6">
          We process account, KYC, session, and ledger data to operate the license and portfolio software. KYC documents
          are stored in object storage or a local encrypted directory in development. We do not log secrets, TOTP seeds,
          or backup codes.
        </p>
      </main>
    </PublicChrome>
  );
}
