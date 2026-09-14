import { PageIntro, PublicChrome } from "@/components/brand";
import { loadConfig } from "@/server/load-config";
import { WHY_NOT_PAID_COPY } from "@/domain/comp/events";

export default async function CompensationPage() {
  const cfg = await loadConfig();
  const treeBps = cfg.tree.l1Bps + cfg.tree.l2Bps + cfg.tree.l3Bps;
  return (
    <PublicChrome>
      <PageIntro
        kicker="Partner network"
        title="Every payout rule, stated exactly"
        body="This page is generated from the same configuration the payout engine enforces. Where copy and policy ever disagree, policy wins and the copy is corrected."
      />
      <main className="mx-auto max-w-6xl space-y-px bg-white/[0.08] px-5 pb-20 text-sm leading-relaxed text-graphite-300">
        <section className="bg-graphite-950 p-6">
          <h2 className="font-display text-lg font-semibold text-[#F3EFE6]">A. Daily portfolio credits</h2>
          <p className="mt-2 text-graphite-400">Settles once per day per active portfolio.</p>
          <ul className="mt-4 space-y-2 text-graphite-400">
            <li>Each active portfolio is credited at the lower of the engine rate and the license daily cap, applied to principal.</li>
            <li>Credits are posted to the Earnings wallet.</li>
            <li>An inactive or lapsed license credits zero.</li>
            <li>Earnings can be moved to Available; withdrawals are requested from Available on a weekly cycle.</li>
            <li>{cfg.copy.dailyCapDisclaimer} Actual credits depend on engine performance and may be zero.</li>
          </ul>
        </section>

        <section className="bg-graphite-950 p-6">
          <h2 className="font-display text-lg font-semibold text-[#F3EFE6]">B. Referral tree — three levels, paid once</h2>
          <p className="mt-2 text-graphite-400">Total {(treeBps / 100).toFixed(2)}% across L1-L3.</p>
          <div className="mt-5 grid gap-px bg-white/[0.08] md:grid-cols-3">
            {[
              ["Level 1", cfg.tree.l1Bps],
              ["Level 2", cfg.tree.l2Bps],
              ["Level 3", cfg.tree.l3Bps],
            ].map(([label, bps]) => (
              <div key={label} className="bg-graphite-950 p-4">
                <div className="font-mono text-[11px] uppercase tracking-ledger text-graphite-500">{label}</div>
                <div className="mt-1 font-display text-2xl font-semibold text-ember">{Number(bps) / 100}%</div>
              </div>
            ))}
          </div>
          <div className="mt-6 grid gap-6 md:grid-cols-2">
            <div>
              <h3 className="font-medium text-[#F3EFE6]">Pays on</h3>
              <ul className="mt-2 list-disc space-y-1 pl-5 text-graphite-400">
                <li>A new portfolio funded by direct deposit.</li>
                <li>Another new portfolio by the same user funded by direct deposit — it pays again on that deposit amount.</li>
                <li>A first-ever license fee, once.</li>
              </ul>
            </div>
            <div>
              <h3 className="font-medium text-[#F3EFE6]">Never pays on</h3>
              <ul className="mt-2 list-disc space-y-1 pl-5 text-graphite-400">
                <li>Any top-up, including a direct-deposit top-up.</li>
                <li>A new portfolio funded from a wallet balance.</li>
                <li>License renewals.</li>
                <li>Daily engine credits.</li>
              </ul>
            </div>
          </div>
          <p className="mt-4 text-graphite-500">
            Compression applies to pay only; an inactive upline is skipped when paying L1-L3. Volume always flows through
            the tree regardless of activity.
          </p>
        </section>

        <section className="bg-graphite-950 p-6">
          <h2 className="font-display text-lg font-semibold text-[#F3EFE6]">C. Volume — PSV and TV</h2>
          <p className="mt-2 text-graphite-400">Only direct deposits create volume.</p>
          <p className="mt-3 text-graphite-400">{cfg.copy.volumeDisclaimer}</p>
          <p className="mt-3 text-graphite-400">
            PSV (Personal Sales Volume): Level 1 direct-deposit amounts only, counting both new portfolios and
            direct-deposit top-ups.
          </p>
          <p className="mt-2 text-graphite-400">TV (Team Volume): Levels 1-7 direct-deposit amounts only. L8+ ignored.</p>
          <p className="mt-2 text-graphite-500">
            Your own principal is your personal book. It is displayed but is never PSV or TV.
          </p>
        </section>

        <section className="bg-graphite-950 p-6">
          <h2 className="font-display text-lg font-semibold text-[#F3EFE6]">D. Fast Start</h2>
          <p className="mt-2 text-graphite-400">{cfg.copy.fastStartOneEarner}</p>
          <p className="mt-2 text-graphite-400">
            Volume A {cfg.fastStart.volumePathA.thresholdCents / 100} USD L1 DD → +{cfg.fastStart.volumePathA.bps / 100}%.
            Path A wins over B. Headcount extra may stack. Raw FS clips at {cfg.fastStart.rawCapBps / 100}%. Tree + FS
            never exceeds {cfg.depositCompCapBps / 100}% of that deposit.
          </p>
        </section>

        <section className="bg-graphite-950 p-6">
          <h2 className="font-display text-lg font-semibold text-[#F3EFE6]">Why tree might not pay</h2>
          <ul className="mt-3 list-disc space-y-1 pl-5 text-graphite-400">
            {Object.entries(WHY_NOT_PAID_COPY).map(([k, v]) => (
              <li key={k}>
                <span className="font-mono text-ember">{k}</span> — {v}
              </li>
            ))}
          </ul>
        </section>
      </main>
    </PublicChrome>
  );
}
