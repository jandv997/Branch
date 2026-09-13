import { SiteFooter, SiteHeader, PatentBadge } from "@/components/brand";

export default function TechnologyPage() {
  return (
    <div className="min-h-screen">
      <SiteHeader />
      <main className="mx-auto max-w-4xl px-5 py-16">
        <PatentBadge />
        <h1 className="mt-4 text-4xl">Technology</h1>
        <p className="mt-4 text-slate-400">
          Qorvex routes a deterministic pipeline across CEX and DEX spot venues: detect, analyze, evaluate, execute,
          reconcile. The engine rate is an admin-set default in v1. Credits posted to user portfolios are always
          min(engine rate, license daily cap) and are expressed as “up to”.
        </p>
        <div className="mt-10 grid gap-4 md:grid-cols-3">
          {["Detect", "Evaluate", "Reconcile"].map((t, i) => (
            <div key={t} className="glass rounded-xl p-5">
              <div className="font-mono text-xs text-cyan">0{i + 1}</div>
              <h2 className="mt-2 text-lg">{t}</h2>
              <p className="mt-2 text-sm text-slate-400">Infrastructure software. Not a promise of profit.</p>
            </div>
          ))}
        </div>
      </main>
      <SiteFooter />
    </div>
  );
}
