import { PageIntro, PublicChrome } from "@/components/brand";
import { LicenseGrid } from "@/components/license-grid";
import { loadConfig } from "@/server/load-config";

export default async function LicensesPage() {
  const cfg = await loadConfig();
  return (
    <PublicChrome>
      <PageIntro
        kicker="Licenses"
        title="One monthly software license, one 12-month portfolio cycle"
        body="The license grants access to the engine. The portfolio is your funded principal, opened for a 12-month cycle and settled daily at the lower of engine rate and your license cap."
      />
      <main className="mx-auto max-w-6xl px-5 pb-20">
        <LicenseGrid licenses={cfg.licenses} />
        <p className="mt-8 max-w-4xl text-[13px] leading-relaxed text-slate-500">{cfg.copy.dailyCapDisclaimer}</p>
      </main>
    </PublicChrome>
  );
}
