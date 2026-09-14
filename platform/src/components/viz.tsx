"use client";

import { useEffect, useRef, useState } from "react";

/** Original hero: venues around a routing core. Not a chart, not an orbital hub. */
export function LiquidityHero() {
  const ref = useRef<HTMLCanvasElement>(null);
  useEffect(() => {
    const canvas = ref.current;
    if (!canvas) return;
    const ctx = canvas.getContext("2d");
    if (!ctx) return;
    let raf = 0;
    const venues = [
      { label: "CEX", a: 0.15 },
      { label: "DEX", a: 0.55 },
      { label: "POOL", a: 1.05 },
      { label: "CHAIN", a: 1.55 },
      { label: "CEX", a: 2.15 },
      { label: "DEX", a: 2.65 },
      { label: "POOL", a: 3.2 },
      { label: "BOOK", a: 3.75 },
    ];
    const packets: { t: number; from: number; life: number }[] = [];
    const tick = (now: number) => {
      const dpr = Math.min(window.devicePixelRatio, 2);
      const w = canvas.clientWidth;
      const h = canvas.clientHeight;
      canvas.width = w * dpr;
      canvas.height = h * dpr;
      ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
      ctx.clearRect(0, 0, w, h);
      const cx = w * 0.5;
      const cy = h * 0.5;
      const r = Math.min(w, h) * 0.34;
      if (packets.length < 10 && Math.random() < 0.08) {
        packets.push({ t: 0, from: Math.floor(Math.random() * venues.length), life: 1 });
      }
      ctx.strokeStyle = "rgba(248,250,254,0.08)";
      ctx.lineWidth = 1;
      venues.forEach((v, i) => {
        const x = cx + Math.cos(v.a) * r;
        const y = cy + Math.sin(v.a) * r;
        ctx.beginPath();
        ctx.moveTo(x, y);
        ctx.lineTo(cx, cy);
        ctx.stroke();
        ctx.fillStyle = i % 2 ? "#347BEC" : "#F8FAFE";
        ctx.fillRect(x - 3, y - 3, 6, 6);
        ctx.fillStyle = "#9BB4CC";
        ctx.font = "10px ui-monospace, monospace";
        ctx.fillText(v.label, x + 8, y + 3);
      });
      ctx.strokeStyle = "#347BEC";
      ctx.strokeRect(cx - 18, cy - 18, 36, 36);
      ctx.fillStyle = "#F8FAFE";
      ctx.font = "11px ui-monospace, monospace";
      ctx.fillText("Q", cx - 4, cy + 4);
      for (let i = packets.length - 1; i >= 0; i--) {
        const p = packets[i]!;
        p.t += 0.012;
        if (p.t >= 1) {
          packets.splice(i, 1);
          continue;
        }
        const v = venues[p.from]!;
        const x0 = cx + Math.cos(v.a) * r;
        const y0 = cy + Math.sin(v.a) * r;
        const x = x0 + (cx - x0) * p.t;
        const y = y0 + (cy - y0) * p.t;
        ctx.fillStyle = "#347BEC";
        ctx.fillRect(x - 1.5, y - 1.5, 3, 3);
      }
      raf = requestAnimationFrame(tick);
      void now;
    };
    raf = requestAnimationFrame(tick);
    return () => cancelAnimationFrame(raf);
  }, []);
  return <canvas ref={ref} className="h-[380px] w-full" aria-hidden />;
}

export function FragmentationViz() {
  const cells = ["CEX books", "DEX pools", "L2 chains", "Pairs", "Depth", "Latency"];
  return (
    <div className="grid grid-cols-2 gap-2 md:grid-cols-3">
      {cells.map((c, i) => (
        <div
          key={c}
          className="border border-white/[0.08] bg-graphite-900 px-4 py-6 font-mono text-[12px] text-graphite-300"
          style={{ animationDelay: `${i * 80}ms` }}
        >
          <div className="mb-3 h-px w-8 bg-ember" />
          {c}
        </div>
      ))}
    </div>
  );
}

export function PipelineViz() {
  const steps = ["Scan", "Analyze", "Route", "Execute", "Verify"];
  return (
    <ol className="grid gap-px bg-white/[0.08] md:grid-cols-5">
      {steps.map((s, i) => (
        <li key={s} className="bg-graphite-950 px-4 py-6">
          <div className="font-mono text-[11px] text-ember">0{i + 1}</div>
          <div className="mt-2 font-display text-xl text-[#F8FAFE]">{s}</div>
        </li>
      ))}
    </ol>
  );
}

export function RouterAB() {
  return (
    <div className="grid items-center gap-3 font-mono text-[12px] md:grid-cols-[1fr_auto_1fr]">
      <div className="border border-white/[0.08] p-6 text-center">
        <div className="kicker">Market A</div>
        <div className="mt-2 text-lg text-[#F8FAFE]">CEX book</div>
        <p className="mt-2 text-graphite-400">Observed print</p>
      </div>
      <div className="flex flex-col items-center gap-1 text-ember">
        <span>↓</span>
        <div className="border border-ember/50 px-4 py-3 text-[#F8FAFE]">AI ROUTER</div>
        <span>↓</span>
      </div>
      <div className="border border-white/[0.08] p-6 text-center">
        <div className="kicker">Market B</div>
        <div className="mt-2 text-lg text-[#F8FAFE]">DEX pool</div>
        <p className="mt-2 text-graphite-400">Paired offset</p>
      </div>
    </div>
  );
}

export type DemoOpportunity = {
  pair: string;
  src: string;
  dst: string;
  spreadBps: number;
  feesBps: number;
  slipBps: number;
  gasUsd: number;
  liq: string;
  conf: number;
  netBps: number;
  status: "WATCH" | "ROUTED" | "SKIPPED";
};

export const DEMO_OPPS: DemoOpportunity[] = [
  { pair: "BTC-USDT", src: "CEX-A", dst: "DEX-B", spreadBps: 14, feesBps: 6, slipBps: 3, gasUsd: 12, liq: "Deep", conf: 62, netBps: 5, status: "WATCH" },
  { pair: "ETH-USDC", src: "DEX-C", dst: "CEX-D", spreadBps: 9, feesBps: 5, slipBps: 4, gasUsd: 8, liq: "Thin", conf: 41, netBps: 0, status: "SKIPPED" },
  { pair: "SOL-USDT", src: "CEX-E", dst: "CEX-F", spreadBps: 11, feesBps: 4, slipBps: 2, gasUsd: 0, liq: "Deep", conf: 71, netBps: 5, status: "ROUTED" },
  { pair: "LINK-USDT", src: "DEX-A", dst: "CEX-B", spreadBps: 7, feesBps: 5, slipBps: 3, gasUsd: 4, liq: "Mid", conf: 38, netBps: -1, status: "SKIPPED" },
];

export function OpportunityTable({ rows = DEMO_OPPS }: { rows?: DemoOpportunity[] }) {
  return (
    <div className="overflow-x-auto border border-white/[0.08]">
      <table className="w-full text-left text-[12px]">
        <thead className="font-mono uppercase tracking-ledger text-graphite-500">
          <tr>
            {["Opportunity", "Spread", "Liquidity", "Fees", "Slippage", "Gas", "Confidence", "Net", "Status"].map((h) => (
              <th key={h} className="p-3 font-normal">
                {h}
              </th>
            ))}
          </tr>
        </thead>
        <tbody>
          {rows.map((r) => (
            <tr key={r.pair + r.src} className="border-t border-white/[0.06] font-mono">
              <td className="p-3 text-[#F8FAFE]">
                {r.pair}
                <div className="text-[10px] text-graphite-500">
                  {r.src} → {r.dst}
                </div>
              </td>
              <td className="p-3">{r.spreadBps} bps</td>
              <td className="p-3">{r.liq}</td>
              <td className="p-3">{r.feesBps} bps</td>
              <td className="p-3">{r.slipBps} bps</td>
              <td className="p-3">${r.gasUsd}</td>
              <td className="p-3">{r.conf}</td>
              <td className="p-3 text-ember">{r.netBps} bps</td>
              <td className="p-3">{r.status}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}

export function VenueNetwork() {
  const nodes = ["CEX", "DEX", "CHAIN", "POOL", "CEX", "DEX"];
  const [ready, setReady] = useState(false);
  useEffect(() => setReady(true), []);
  if (!ready) return <div className="relative h-56 border border-white/[0.08] bg-graphite-900" />;
  return (
    <div className="relative h-56 border border-white/[0.08] bg-graphite-900">
      <svg viewBox="0 0 400 160" className="h-full w-full">
        {nodes.map((_, i) => {
          const a = (i / nodes.length) * Math.PI * 2;
          const x = 200 + Math.cos(a) * 90;
          const y = 80 + Math.sin(a) * 48;
          return (
            <g key={i}>
              <line x1="200" y1="80" x2={x.toFixed(2)} y2={y.toFixed(2)} stroke="rgba(248,250,254,0.12)" />
              <rect x={(x - 4).toFixed(2)} y={(y - 4).toFixed(2)} width="8" height="8" fill={i % 2 ? "#347BEC" : "#F8FAFE"} />
            </g>
          );
        })}
        <rect x="188" y="68" width="24" height="24" fill="none" stroke="#347BEC" />
      </svg>
    </div>
  );
}
