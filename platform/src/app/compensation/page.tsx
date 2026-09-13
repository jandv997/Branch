import { PageIntro, PublicChrome } from "@/components/brand";
import { loadConfig } from "@/server/load-config";
import { WHY_NOT_PAID_COPY } from "@/domain/comp/events";

export default async function CompensationPage() {
  const cfg = await loadConfig();
  const treeBps = cfg.tree.l1Bps + cfg.tree.l2Bps + cfg.tree.l3Bps;
  return (
    <PublicChrome>
      <PageIntro
        kicker="Compensation"
        title="Every payout rule, stated exactly"
        body="This page is generated from the same configuration the payout engine enforces. Where copy and policy ever disagree, policy wins and the copy is corrected."
      />
      <main className="mx-auto max-w-6xl space-y-4 px-5 pb-20 text-sm leading-relaxed text-slate-300">
        <section className="glass rounded-2xl p-6">
          <h2 className="text-lg font-semibold text-white">A. Daily portfolio credits</h2>
          <p className="mt-2 text-slate-400">Settles once per day per active portfolio.</p>
          <ul className="mt-4 space-y-2 text-slate-400">
            <li>Each active portfolio is credited at the lower of the engine rate and the license daily cap, applied to principal.</li>
            <li>Credits are posted to the Earnings wallet.</li>
            <li>An inactive or lapsed license credits zero.</li>
            <li>Earnings can be moved to Available; withdrawals are requested from Available on a weekly cycle.</li>
            <li>{cfg.copy.dailyCapDisclaimer} Actual credits depend on engine performance and may be zero.</li>
          </ul>
        </section>

        <section className="glass rounded-2xl p-6">
          <h2 className="text-lg font-semibold text-white">B. Referral tree — three levels, paid once</h2>
          <p className="mt-2 text-slate-400">Total {(treeBps / 100).toFixed(2)}% across L1-L3.</p>
          <div className="mt-5 grid gap-3 md:grid-cols-3">
            {[
              ["Level 1", cfg.tree.l1Bps],
              ["Level 2", cfg.tree.l2Bps],
              ["Level 3", cfg.tree.l3Bps],
            ].map(([label, bps]) => (
              <div key={label} className="rounded-xl border border-white/10 bg-navy-950 p-4">
                <div className="text-[11px] uppercase tracking-ledger text-slate-500">{label}</div>
                <div className="mt-1 text-2xl font-semibold text-cyan">{Number(bps) / 100}%</div>
              </div>
            ))}
          </div>
          <div className="mt-6 grid gap-6 md:grid-cols-2">
            <div>
              <h3 className="font-medium text-white">Pays on</h3>
              <ul className="mt-2 list-disc space-y-1 pl-5 text-slate-400">
                <li>A new portfolio funded by direct deposit.</li>
                <li>Another new portfolio by the same user funded by direct deposit — it pays again on that deposit amount.</li>
                <li>A first-ever license fee, once.</li>
              </ul>
            </div>
            <div>
              <h3 className="font-medium text-white">Never pays on</h3>
              <ul className="mt-2 list-disc space-y-1 pl-5 text-slate-400">
                <li>Any top-up, including a direct-deposit top-up.</li>
                <li>A new portfolio funded from a wallet balance.</li>
                <li>License renewals.</li>
                <li>Daily engine credits.</li>
              </ul>
            </div>
          </div>
          <p className="mt-4 text-slate-500">
            Compression applies to pay only; an inactive upline is skipped when paying L1-L3. Volume always flows through
            the tree regardless of activity.
          </p>
        </section>

        <section className="glass rounded-2xl p-6">
          <h2 className="text-lg font-semibold text-white">C. Volume — PSV and TV</h2>
          <p className="mt-2 text-slate-400">Only direct deposits create volume.</p>
          <p className="mt-3 text-slate-400">{cfg.copy.volumeDisclaimer}</p>
          <p className="mt-3 text-slate-400">
            PSV (Personal Sales Volume): Level 1 direct-deposit amounts only, counting both new portfolios and
            direct-deposit top-ups.
          </p>
          <p className="mt-2 text-slate-400">TV (Team Volume): Levels 1-7 direct-deposit amounts only. L8+ ignored.</p>
          <p className="mt-2 text-slate-500">
            Your own principal is your personal book. It is displayed but is never PSV or TV.
          </p>
        </section>

        <section className="glass rounded-2xl p-6">
          <h2 className="text-lg font-semibold text-white">D. Fast Start</h2>
          <p className="mt-2 text-slate-400">{cfg.copy.fastStartOneEarner}</p>
          <p className="mt-2 text-slate-400">
            Volume A {cfg.fastStart.volumePathA.thresholdCents / 100} USD L1 DD → +{cfg.fastStart.volumePathA.bps / 100}%.
            Path A wins over B. Headcount extra may stack. Raw FS clips at {cfg.fastStart.rawCapBps / 100}%. Tree + FS
            never exceeds {cfg.depositCompCapBps / 100}% of that deposit.
          </p>
        </section>

        <section className="glass rounded-2xl p-6">
          <h2 className="text-lg font-semibold text-white">Why tree might not pay</h2>
          <ul className="mt-3 list-disc space-y-1 pl-5 text-slate-400">
            {Object.entries(WHY_NOT_PAID_COPY).map(([k, v]) => (
              <li key={k}>
                <span className="font-mono text-cyan">{k}</span> — {v}
              </li>
            ))}
          </ul>
        </section>
      </main>
    </PublicChrome>
  );
}
