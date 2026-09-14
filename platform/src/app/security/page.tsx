import { PageIntro, PublicChrome } from "@/components/brand";
import { Fingerprint, KeyRound, Shield, Timer } from "lucide-react";

const FEATURES = [
  [
    Shield,
    "2FA before the first withdrawal",
    "Time-based one-time codes are mandatory before any withdrawal can be requested, with single-use backup codes issued at setup.",
  ],
  [
    Timer,
    "24-hour whitelist lock",
    "A newly added withdrawal address cannot be paid for 24 hours. Existing addresses continue to work during the lock.",
  ],
  [
    KeyRound,
    "Session and device visibility",
    "Active sessions, sign-in IP history and device fingerprints are visible in your account, and any session can be revoked.",
  ],
  [
    Fingerprint,
    "Identity verification gates",
    "Identity documents are required before withdrawals above the configured threshold, and are stored in restricted object storage.",
  ],
] as const;

export default function SecurityPage() {
  return (
    <PublicChrome>
      <PageIntro
        kicker="Security"
        title="Controls on the account, the money and the operators"
        body="Every balance change writes an immutable ledger row and an audit row. Administrative actions record the operator, the before and after values, the reason and the IP address."
      />
      <main className="mx-auto max-w-6xl px-5 pb-20">
        <div className="grid gap-px bg-white/[0.08] md:grid-cols-2">
        {FEATURES.map(([Icon, title, body]) => (
          <article key={title} className="bg-graphite-950 p-6">
            <Icon className="h-5 w-5 text-ember" />
            <h2 className="mt-4 font-display text-lg font-semibold text-[#F8FAFE]">{title}</h2>
            <p className="mt-2 text-sm leading-relaxed text-graphite-400">{body}</p>
          </article>
        ))}
        </div>
      </main>
    </PublicChrome>
  );
}
