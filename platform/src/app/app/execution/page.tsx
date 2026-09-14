"use client";

import { Card } from "@/components/ui";
import { SimBadge } from "@/components/mark";
import { useState } from "react";

const EVENTS = [
  { id: "ex-1", time: "09:14:02Z", asset: "BTC-USDT", route: "CEX-A → DEX-B", size: "0.40", status: "ABORTED", fees: "6 bps", slip: "3 bps", result: "Spread collapsed" },
  { id: "ex-2", time: "09:11:44Z", asset: "SOL-USDT", route: "CEX-E → CEX-F", size: "12.0", status: "SIMULATED", fees: "4 bps", slip: "2 bps", result: "Cost model only" },
  { id: "ex-3", time: "08:58:19Z", asset: "ETH-USDC", route: "DEX-C → CEX-D", size: "3.2", status: "SKIPPED", fees: "5 bps", slip: "4 bps", result: "Failed liquidity check" },
];

const TIMELINE: Record<string, string[]> = {
  "ex-1": ["Market data tick", "Opportunity scored", "Risk check: latency", "Abort: spread collapse", "No fill"],
  "ex-2": ["Market data tick", "Cost model", "Simulation only — venue adapter not connected"],
  "ex-3": ["Candidate detected", "Liquidity check fail", "Not sent"],
};

export default function ExecutionPage() {
  const [open, setOpen] = useState<string | null>(EVENTS[0]!.id);
  return (
    <div className="space-y-6">
      <div className="flex flex-wrap items-end justify-between gap-3">
        <div>
          <p className="kicker">Execution</p>
          <h1 className="mt-2 font-display text-3xl font-semibold">Event tape</h1>
          <p className="mt-2 max-w-xl text-sm text-graphite-400">
            Active and historical execution events. Production fills will attach here when venue adapters are live.
          </p>
        </div>
        <SimBadge>Venue adapters not connected</SimBadge>
      </div>
      <div className="overflow-x-auto border border-white/[0.08]">
        <table className="w-full text-left text-[12px]">
          <thead className="font-mono uppercase tracking-ledger text-graphite-500">
            <tr>
              {["Time", "Asset", "Route", "Size", "Status", "Fees", "Slippage", "Result"].map((h) => (
                <th key={h} className="p-3 font-normal">
                  {h}
                </th>
              ))}
            </tr>
          </thead>
          <tbody>
            {EVENTS.map((e) => (
              <tr
                key={e.id}
                className="cursor-pointer border-t border-white/[0.06] font-mono hover:bg-white/[0.02]"
                onClick={() => setOpen(e.id)}
              >
                <td className="p-3 text-graphite-400">{e.time}</td>
                <td className="p-3">{e.asset}</td>
                <td className="p-3">{e.route}</td>
                <td className="p-3">{e.size}</td>
                <td className="p-3 text-ember">{e.status}</td>
                <td className="p-3">{e.fees}</td>
                <td className="p-3">{e.slip}</td>
                <td className="p-3 text-graphite-400">{e.result}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
      {open ? (
        <Card>
          <h2 className="font-display text-lg">Timeline · {open}</h2>
          <ol className="mt-4 space-y-2 font-mono text-[12px] text-graphite-300">
            {(TIMELINE[open] ?? []).map((s, i) => (
              <li key={s}>
                <span className="text-ember">0{i + 1}</span> {s}
              </li>
            ))}
          </ol>
        </Card>
      ) : null}
    </div>
  );
}
