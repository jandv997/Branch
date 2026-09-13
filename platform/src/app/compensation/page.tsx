import { SiteFooter, SiteHeader, PatentBadge } from "@/components/brand";
import { loadConfig } from "@/server/load-config";
import { WHY_NOT_PAID_COPY } from "@/domain/comp/events";

export default async function CompensationPage() {
  const cfg = await loadConfig();
  return (
    <div className="min-h-screen">
      <SiteHeader />
      <main className="mx-auto max-w-4xl px-5 py-16">
        <PatentBadge />
        <h1 className="mt-4 text-4xl">Compensation</h1>
        <p className="mt-3 text-slate-400">
          These rules are generated from the same backend policy module that posts ledger rows. The UI does not invent payouts.
        </p>
        <section className="mt-10 space-y-6 text-sm text-slate-300">
          <div className="glass rounded-xl p-5">
            <h2 className="text-lg text-white">Daily credits</h2>
            <p className="mt-2">{cfg.copy.dailyCapDisclaimer}</p>
            <p className="mt-2">
              Applied bps = min(engine {cfg.engineDefaultBps} bps, license cap). Inactive license = 0.
            </p>
          </div>
          <div className="glass rounded-xl p-5">
            <h2 className="text-lg text-white">Referral tree (3 levels, paid once per qualifying event)</h2>
            <p className="mt-2 font-mono text-cyan">
              L1 {cfg.tree.l1Bps / 100}% / L2 {cfg.tree.l2Bps / 100}% / L3 {cfg.tree.l3Bps / 100}% = {(cfg.tree.l1Bps + cfg.tree.l2Bps + cfg.tree.l3Bps) / 100}%
            </p>
            <ul className="mt-3 list-disc space-y-1 pl-5 text-slate-400">
              <li>Pays only if NEW PORTFOLIO + source DIRECT_DEPOSIT</li>
              <li>Does not pay: any top-up (including DD top-up), wallet-funded new portfolio, license renewal, daily credits</li>
              <li>First-ever license fee: tree pays once; renewals do not</li>
              <li>Compression skips inactive for PAY only. Volume always flows.</li>
            </ul>
          </div>
          <div className="glass rounded-xl p-5">
            <h2 className="text-lg text-white">PSV / TV</h2>
            <p className="mt-2">{cfg.copy.volumeDisclaimer}</p>
            <p className="mt-2">PSV = L1 DIRECT_DEPOSIT only. TV = L1–L7 DIRECT_DEPOSIT only. L8+ ignored. Own book ≠ TV.</p>
          </div>
          <div className="glass rounded-xl p-5">
            <h2 className="text-lg text-white">Fast Start (days 1–14 from activation)</h2>
            <p className="mt-2">{cfg.copy.fastStartOneEarner}</p>
            <p className="mt-2">
              Volume A {cfg.fastStart.volumePathA.thresholdCents / 100} USD L1 DD → +{cfg.fastStart.volumePathA.bps / 100}%. Path A wins over B.
              Headcount extra may stack. Raw FS clips at {cfg.fastStart.rawCapBps / 100}%. Tree + FS never exceeds {cfg.depositCompCapBps / 100}% of that deposit.
            </p>
          </div>
          <div className="glass rounded-xl p-5">
            <h2 className="text-lg text-white">Why tree might not pay</h2>
            <ul className="mt-2 list-disc space-y-1 pl-5 text-slate-400">
              {Object.entries(WHY_NOT_PAID_COPY).map(([k, v]) => (
                <li key={k}>
                  <span className="font-mono text-cyan">{k}</span> — {v}
                </li>
              ))}
            </ul>
          </div>
        </section>
      </main>
      <SiteFooter />
    </div>
  );
}
