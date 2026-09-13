import { PublicChrome, PatentBadge } from "@/components/brand";
import { prisma } from "@/server/db";
import { loadConfig } from "@/server/load-config";

export const dynamic = "force-dynamic";

export default async function UpdatesPage() {
  const cfg = await loadConfig();
  let announcements: { id: string; title: string; body: string; createdAt: Date }[] = [];
  let jobs: { id: string; name: string; status: string; startedAt: Date }[] = [];
  let cms: { title: string; body: unknown; updatedAt: Date } | null = null;
  let configUpdated: Date | null = null;
  try {
    announcements = await prisma.announcement.findMany({
      where: { active: true },
      orderBy: { createdAt: "desc" },
      take: 20,
    });
    jobs = await prisma.jobRun.findMany({
      orderBy: { startedAt: "desc" },
      take: 12,
      select: { id: true, name: true, status: true, startedAt: true },
    });
    cms = await prisma.cmsPage.findUnique({ where: { slug: "updates" } });
    const row = await prisma.compConfigRow.findUnique({ where: { id: "singleton" } });
    configUpdated = row?.updatedAt ?? null;
  } catch {
    /* public page still renders from config when DB is down */
  }
  const extras = Array.isArray(cms?.body) ? (cms!.body as { title: string; body: string; at?: string }[]) : [];
  return (
    <PublicChrome>
      <main className="mx-auto max-w-4xl px-5 py-16">
        <PatentBadge />
        <h1 className="mt-4 font-display text-4xl tracking-tight">Updates</h1>
        <p className="mt-3 text-sm text-ink-500">
          Operational changelog for the Qorvex license + portfolio software. Daily credits remain capped (“up to”).
          Compensation rules live in the backend policy module — this page does not invent payouts.
        </p>
        <section className="mt-8 grid gap-4 md:grid-cols-3">
          <div className="border border-ink-900/12 bg-paper-50 p-4">
            <div className="font-mono text-[10px] uppercase tracking-ledger text-ink-400">Engine default</div>
            <div className="mt-1 font-mono text-copper">up to {(cfg.engineDefaultBps / 100).toFixed(2)}%</div>
          </div>
          <div className="border border-ink-900/12 bg-paper-50 p-4">
            <div className="font-mono text-[10px] uppercase tracking-ledger text-ink-400">Comp config</div>
            <div className="mt-1 font-mono text-xs">{configUpdated ? configUpdated.toISOString() : "defaults"}</div>
          </div>
          <div className="border border-ink-900/12 bg-paper-50 p-4">
            <div className="font-mono text-[10px] uppercase tracking-ledger text-ink-400">Deposit comp cap</div>
            <div className="mt-1 font-mono text-copper">{cfg.depositCompCapBps / 100}%</div>
          </div>
        </section>
        <section className="mt-10">
          <h2 className="font-display text-2xl">Announcements</h2>
          {!announcements.length && !extras.length ? (
            <p className="mt-3 text-sm text-ink-400">No published announcements yet. Superadmin posts them from Admin → CMS / announcement.</p>
          ) : (
            <div className="mt-4 space-y-3">
              {announcements.map((a) => (
                <article key={a.id} className="border border-ink-900/12 bg-paper-50 p-5">
                  <div className="font-mono text-[10px] uppercase tracking-ledger text-ink-400">{a.createdAt.toISOString()}</div>
                  <h3 className="mt-1 font-display text-xl">{a.title}</h3>
                  <p className="mt-2 text-sm text-ink-500">{a.body}</p>
                </article>
              ))}
              {extras.map((e) => (
                <article key={e.title} className="border border-ink-900/12 bg-paper-50 p-5">
                  <div className="font-mono text-[10px] uppercase tracking-ledger text-ink-400">{e.at}</div>
                  <h3 className="mt-1 font-display text-xl">{e.title}</h3>
                  <p className="mt-2 text-sm text-ink-500">{e.body}</p>
                </article>
              ))}
            </div>
          )}
        </section>
        <section className="mt-10">
          <h2 className="font-display text-2xl">Job runs</h2>
          <p className="text-xs text-ink-400">Idempotent worker / CLI. Replayable via `job_runs`.</p>
          <div className="mt-3 overflow-x-auto border border-ink-900/12 bg-paper-50">
            <table className="w-full text-left text-xs">
              <thead className="font-mono uppercase tracking-ledger text-ink-400">
                <tr>
                  <th className="p-2">When</th>
                  <th className="p-2">Job</th>
                  <th className="p-2">Status</th>
                </tr>
              </thead>
              <tbody>
                {jobs.map((j) => (
                  <tr key={j.id} className="border-t border-ink-900/10 font-mono">
                    <td className="p-2 text-ink-400">{j.startedAt.toISOString()}</td>
                    <td className="p-2">{j.name}</td>
                    <td className="p-2 text-copper">{j.status}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </section>
      </main>
    </PublicChrome>
  );
}
