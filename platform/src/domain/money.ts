/**
 * Typed money in integer USD cents. All compensation math goes through Money.
 * Rounding: floor (conservative credits / payouts).
 */
export class Money {
  readonly cents: bigint;

  private constructor(cents: bigint) {
    if (!Number.isNaN(Number(cents)) && cents < 0n) {
      throw new Error("Money cannot be negative");
    }
    this.cents = cents;
  }

  static readonly ZERO = new Money(0n);

  static fromCents(cents: bigint | number | string): Money {
    return new Money(BigInt(cents));
  }

  static fromDollars(amount: number | string): Money {
    if (typeof amount === "number") {
      if (!Number.isFinite(amount)) throw new Error("Invalid dollar amount");
      const cents = Math.trunc(Math.round(amount * 100));
      if (cents < 0) throw new Error("Money cannot be negative");
      return new Money(BigInt(cents));
    }
    const t = amount.trim();
    const neg = t.startsWith("-");
    const s = neg ? t.slice(1) : t;
    const [w, f = ""] = s.split(".");
    const frac = (f + "00").slice(0, 2);
    const cents = BigInt(w || "0") * 100n + BigInt(frac);
    if (neg) throw new Error("Money cannot be negative");
    return new Money(cents);
  }

  add(other: Money): Money {
    return new Money(this.cents + other.cents);
  }

  sub(other: Money): Money {
    const v = this.cents - other.cents;
    if (v < 0n) throw new Error("Money subtraction underflow");
    return new Money(v);
  }

  min(other: Money): Money {
    return this.cents <= other.cents ? this : other;
  }

  max(other: Money): Money {
    return this.cents >= other.cents ? this : other;
  }

  /** Apply basis points, floor. 10_000 bps = 100%. */
  pctBps(bps: number): Money {
    if (!Number.isInteger(bps) || bps < 0) throw new Error("bps must be a non-negative integer");
    return new Money((this.cents * BigInt(bps)) / 10_000n);
  }

  gt(other: Money): boolean {
    return this.cents > other.cents;
  }

  gte(other: Money): boolean {
    return this.cents >= other.cents;
  }

  eq(other: Money): boolean {
    return this.cents === other.cents;
  }

  isZero(): boolean {
    return this.cents === 0n;
  }

  toNumber(): number {
    return Number(this.cents);
  }

  toDollars(): string {
    const sign = this.cents < 0n ? "-" : "";
    const abs = this.cents < 0n ? -this.cents : this.cents;
    const w = abs / 100n;
    const f = (abs % 100n).toString().padStart(2, "0");
    return `${sign}${w}.${f}`;
  }

  formatUsd(): string {
    const [w, f] = this.toDollars().split(".");
    const n = Number(w);
    const grouped = Number.isFinite(n)
      ? Math.abs(n).toLocaleString("en-US")
      : w;
    const sign = this.cents < 0n ? "-" : "";
    return `${sign}$${grouped}.${f}`;
  }

  toJSON(): string {
    return this.cents.toString();
  }
}

export function centsToUsd(cents: bigint | number | string): string {
  return Money.fromCents(cents).formatUsd();
}
