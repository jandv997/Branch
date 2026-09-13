import { PublicChrome, PatentBadge } from "@/components/brand";

export default function TermsPage() {
  return (
    <PublicChrome>
      <main className="mx-auto max-w-3xl px-5 py-16 text-sm leading-relaxed text-slate-400">
        <PatentBadge />
        <h1 className="mt-4 text-4xl font-semibold tracking-tight text-white">Terms of software license</h1>
        <p className="mt-6">
          Qorvex AI licenses software. License fees are not an investment product. Daily credits are discretionary
          infrastructure outputs capped (“up to”) and are not a guaranteed ROI. You may lose capital. Nothing here is
          financial, legal, or tax advice. Patent pending.
        </p>
      </main>
    </PublicChrome>
  );
}
