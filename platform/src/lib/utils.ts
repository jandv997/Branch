import { clsx, type ClassValue } from "clsx";
import { twMerge } from "tailwind-merge";

export function cn(...inputs: ClassValue[]) {
  return twMerge(clsx(inputs));
}

export function usd(cents: bigint | number | string | undefined | null): string {
  if (cents === undefined || cents === null) return "$0.00";
  const n = BigInt(cents);
  const sign = n < 0n ? "-" : "";
  const abs = n < 0n ? -n : n;
  const w = abs / 100n;
  const f = (abs % 100n).toString().padStart(2, "0");
  return `${sign}$${w.toLocaleString("en-US")}.${f}`;
}

export function usdPlain(cents: bigint | number | string | undefined | null): string {
  return usd(cents).replace(/\.00$/, "");
}

export function bpsLabel(bps: number): string {
  return `up to ${(bps / 100).toFixed(2)}%`;
}
