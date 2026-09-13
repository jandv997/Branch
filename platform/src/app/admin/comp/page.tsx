"use client";

import { Button, Card, Input, Label, Textarea } from "@/components/ui";
import { trpc } from "@/trpc/client";
import { usd } from "@/lib/utils";
import { useEffect, useState } from "react";

export default function CompEditor() {
  const get = trpc.admin.configGet.useQuery();
  const put = trpc.admin.configPut.useMutation();
  const [json, setJson] = useState("");
  const [dep, setDep] = useState("1000");
  const [tree, setTree] = useState("175");
  const [fs, setFs] = useState("1000");
  const dry = trpc.admin.dryRun.useQuery(
    {
      depositCents: String(Math.round(Number(dep) * 100)),
      treePaidCents: String(Math.round(Number(tree) * 100)),
      fastStartRawBps: Number(fs),
    },
    { enabled: true },
  );
  useEffect(() => {
    if (get.data) setJson(JSON.stringify(get.data, null, 2));
  }, [get.data]);
  return (
    <div className="space-y-6">
      <h1 className="text-2xl">Compensation config</h1>
      <p className="text-xs text-slate-500">Singleton JSON. Public compensation/ranks pages read the same object. Policy wins if copy disagrees.</p>
      <Textarea className="min-h-[420px] font-mono text-[11px]" value={json} onChange={(e) => setJson(e.target.value)} />
      <Button
        onClick={() => {
          try {
            put.mutate(JSON.parse(json));
          } catch {
            alert("Invalid JSON");
          }
        }}
      >
        Save
      </Button>
      {put.error ? <p className="text-xs text-red-300">{put.error.message}</p> : null}
      <Card>
        <h2 className="text-sm">Dry-run 25% clip calculator</h2>
        <div className="mt-3 grid gap-3 md:grid-cols-3">
          <div>
            <Label>Deposit USD</Label>
            <Input value={dep} onChange={(e) => setDep(e.target.value)} />
          </div>
          <div>
            <Label>Tree already USD</Label>
            <Input value={tree} onChange={(e) => setTree(e.target.value)} />
          </div>
          <div>
            <Label>FS raw bps</Label>
            <Input value={fs} onChange={(e) => setFs(e.target.value)} />
          </div>
        </div>
        {dry.data ? (
          <pre className="mt-3 text-xs">
            FS {usd(dry.data.fastStartCents)} · total {usd(dry.data.totalCents)} / cap {usd(dry.data.capCents)} · {dry.data.clipReason}
          </pre>
        ) : null}
      </Card>
    </div>
  );
}
