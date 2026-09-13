import { SiteFooter, SiteHeader, PatentBadge } from "@/components/brand";
import { prisma } from "@/server/db";
import { loadConfig } from "@/server/load-config";

export const dynamic = "force-dynamic";

export default async function UpdatesPage() {
  const cfg = await loadConfig();
  let announcements: { id: string; title: string; body: string; createdAt: Date }[] = [];
  let jobs: { id: string; name: string; status: string; startedAt: Date; stats: unknown }[] = [];
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
      select: { id: true, name: true, status: true, startedAt: true, stats: true },
    });
    cms = await prisma.cmsPage.findUnique({ where: { slug: "updates" } });
    const row = await prisma.compConfigRow.findUnique({ where: { id: "singleton" } });
    configUpdated = row?.updatedAt ?? null;
  } catch {
    /* public page still renders from config when DB is down */
  }
  const extras = Array.isArray(cms?.body) ? (cms!.body as { title: string; body: string; at?: string }[]) : [];
  return (
    <div className="min-h-screen">
      <SiteHeader />
      <main className="mx-auto max-w-4xl px-5 py-16">
        <PatentBadge />
        <h1 className="mt-4 text-4xl">Updates</h1>
        <p className="mt-3 text-sm text-slate-400">
          Operational changelog for the Qorvex license + portfolio software. Daily credits remain capped (“up to”).
          Compensation rules live in the backend policy module — this page does not invent payouts.
        </p>
        <section className="mt-8 grid gap-4 md:grid-cols-3">
          <div className="glass rounded-xl p-4">
            <div className="text-[10px] uppercase tracking-widest text-slate-500">Engine default</div>
            <div className="mt-1 font-mono text-cyan">up to {(cfg.engineDefaultBps / 100).toFixed(2)}%</div>
          </div>
          <div className="glass rounded-xl p-4">
            <div className="text-[10px] uppercase tracking-widest text-slate-500">Comp config</div>
            <div className="mt-1 font-mono text-xs">{configUpdated ? configUpdated.toISOString() : "defaults"}</div>
          </div>
          <div className="glass rounded-xl p-4">
            <div className="text-[10px] uppercase tracking-widest text-slate-500">Deposit comp cap</div>
            <div className="mt-1 font-mono text-cyan">{cfg.depositCompCapBps / 100}%</div>
          </div>
        </section>
        <section className="mt-10">
          <h2 className="text-lg">Announcements</h2>
          {!announcements.length && !extras.length ? (
            <p className="mt-3 text-sm text-slate-500">No published announcements yet. Superadmin posts them from Admin → CMS / announcement.</p>
          ) : (
            <div className="mt-4 space-y-3">
              {announcements.map((a) => (
                <article key={a.id} className="glass rounded-xl p-5">
                  <div className="text-[10px] uppercase tracking-widest text-slate-500">{a.createdAt.toISOString()}</div>
                  <h3 className="mt-1 text-white">{a.title}</h3>
                  <p className="mt-2 text-sm text-slate-400">{a.body}</p>
                </article>
              ))}
              {extras.map((e) => (
                <article key={e.title} className="glass rounded-xl p-5">
                  <div className="text-[10px] uppercase tracking-widest text-slate-500">{e.at}</div>
                  <h3 className="mt-1 text-white">{e.title}</h3>
                  <p className="mt-2 text-sm text-slate-400">{e.body}</p>
                </article>
              ))}
            </div>
          )}
        </section>
        <section className="mt-10">
          <h2 className="text-lg">Job runs</h2>
          <p className="text-xs text-slate-500">Idempotent worker / CLI. Replayable via `job_runs`.</p>
          <div className="mt-3 overflow-x-auto">
            <table className="w-full text-left text-xs">
              <thead className="uppercase tracking-widest text-slate-500">
                <tr>
                  <th className="p-2">When</th>
                  <th className="p-2">Job</th>
                  <th className="p-2">Status</th>
                </tr>
              </thead>
              <tbody>
                {jobs.map((j) => (
                  <tr key={j.id} className="border-t border-white/10 font-mono">
                    <td className="p-2 text-slate-500">{j.startedAt.toISOString()}</td>
                    <td className="p-2">{j.name}</td>
                    <td className="p-2 text-cyan">{j.status}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </section>
      </main>
      <SiteFooter />
    </div>
  );
}
