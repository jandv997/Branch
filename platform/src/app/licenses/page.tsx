import { PageIntro, PublicChrome } from "@/components/brand";
import { LicenseGrid } from "@/components/license-grid";
import { loadConfig } from "@/server/load-config";

export default async function LicensesPage() {
  const cfg = await loadConfig();
  return (
    <PublicChrome>
      <PageIntro
        kicker="Licenses"
        title="A 12-month software license. Monthly payment options."
        body="License fees buy software access. Portfolio funding is separate. Tiers and prices are loaded from the same admin-configurable compensation table the engine uses."
      />
      <main className="mx-auto max-w-6xl px-5 pb-20">
        <LicenseGrid licenses={cfg.licenses} />
        <p className="mt-8 max-w-4xl text-[13px] leading-relaxed text-graphite-500">{cfg.copy.dailyCapDisclaimer}</p>
      </main>
    </PublicChrome>
  );
}
