import { PublicChrome, PatentBadge } from "@/components/brand";

export default function AmlPage() {
  return (
    <PublicChrome>
      <main className="mx-auto max-w-3xl px-5 py-16 text-sm leading-relaxed text-slate-400">
        <PatentBadge />
        <h1 className="mt-4 text-4xl font-semibold tracking-tight text-white">AML / KYC</h1>
        <p className="mt-6">
          Withdrawals above the configured threshold require approved KYC. We may freeze accounts, reject deposits, or
          delay withdrawals to meet compliance obligations. Whitelist addresses have a 24-hour unlock delay.
        </p>
      </main>
    </PublicChrome>
  );
}
