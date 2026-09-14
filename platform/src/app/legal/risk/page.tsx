import { PublicChrome, PatentBadge } from "@/components/brand";

export default function RiskPage() {
  return (
    <PublicChrome>
      <main className="mx-auto max-w-3xl px-5 py-16 text-sm leading-relaxed text-slate-400">
        <PatentBadge />
        <h1 className="font-display text-4xl font-semibold tracking-tight text-[#F8FAFE]">Risk disclosure</h1>
        <p className="mt-6">
          Automated cryptocurrency trading involves substantial risk of loss. Market-neutral strategies can still lose
          money due to latency, fees, venue outages, inventory, and model error. We do not describe the product as
          guaranteed, risk-free, or riskless arb. Daily percentages shown are caps (“up to”).
        </p>
      </main>
    </PublicChrome>
  );
}
