"use client";

const CEX = [
  ["BTC-USDT", "BINANCE", "OKX", 12],
  ["ETH-USDT", "COINBASE", "KRAKEN", 8],
  ["SOL-USDT", "OKX", "BYBIT", 15],
  ["XRP-USDT", "KRAKEN", "GATE", 6],
  ["LINK-USDT", "BINANCE", "COINBASE", 9],
  ["AVAX-USDT", "BYBIT", "OKX", 11],
];

const DEX = [
  ["WETH/USDC", "UNISWAP", "SUSHISWAP", 14],
  ["WBTC/USDT", "CURVE", "UNISWAP", 7],
  ["SOL/USDC", "ORCA", "RAYDIUM", 18],
  ["ARB/USDC", "CAMELOT", "UNISWAP", 10],
  ["OP/USDT", "VELODROME", "UNISWAP", 5],
  ["MATIC/USDC", "QUICKSWAP", "UNISWAP", 8],
];

function Tape({
  title,
  rows,
}: {
  title: string;
  rows: typeof CEX;
}) {
  return (
    <div className="min-w-0 flex-1">
      <div className="flex items-center justify-between border-b border-ink-900/15 pb-2">
        <span className="kicker">{title}</span>
        <span className="font-mono text-[10px] text-ink-400">bps · illustrative</span>
      </div>
      <ol className="mt-3 space-y-2">
        {rows.map(([pair, a, b, bps], i) => (
          <li key={pair} className="grid grid-cols-[1fr_auto] items-baseline gap-3 font-mono text-[12px]">
            <div>
              <div className="text-ink-900">{pair}</div>
              <div className="text-[10px] uppercase tracking-wide text-ink-400">
                {a} → {b}
              </div>
            </div>
            <div className="text-right">
              <span className="text-copper">+{bps}</span>
              <span className="ml-1 text-[10px] text-ink-400">bps</span>
              <div
                className="mt-1 h-px bg-copper"
                style={{ width: `${28 + ((i * 13) % 40)}px`, marginLeft: "auto", animationDelay: `${i * 120}ms` }}
              />
            </div>
          </li>
        ))}
      </ol>
    </div>
  );
}

export function EngineVisual() {
  return (
    <div className="ledger-rules border border-ink-900/15 bg-paper-50 p-5 shadow-stamp" aria-hidden>
      <div className="flex items-center justify-between gap-3 border-b border-ink-900/15 pb-3">
        <div>
          <div className="font-display text-lg text-ink-900">Spot blotter</div>
          <div className="font-mono text-[10px] uppercase tracking-ledger text-ink-400">CEX · DEX · illustrative tape</div>
        </div>
        <span className="inline-flex items-center gap-2 font-mono text-[10px] uppercase tracking-ledger text-copper">
          <span className="h-1.5 w-1.5 bg-copper" />
          Engine operational
        </span>
      </div>
      <div className="mt-5 flex flex-col gap-8 md:flex-row">
        <Tape title="CEX" rows={CEX} />
        <div className="hidden w-px bg-ink-900/10 md:block" />
        <Tape title="DEX" rows={DEX} />
      </div>
      <p className="mt-5 font-mono text-[10px] leading-relaxed text-ink-400">
        Figures are typeset samples of spread structure. Credits posted to a book are always min(engine, license cap)
        and are expressed as “up to”.
      </p>
    </div>
  );
}
