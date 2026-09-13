"use client";

import { cn } from "@/lib/utils";
import type { ButtonHTMLAttributes, InputHTMLAttributes, ReactNode, SelectHTMLAttributes, TextareaHTMLAttributes } from "react";

export function Button({
  className,
  variant = "primary",
  ...props
}: ButtonHTMLAttributes<HTMLButtonElement> & { variant?: "primary" | "ghost" | "danger" | "outline" }) {
  const styles = {
    primary: "bg-copper text-paper-50 hover:bg-copper-dim",
    ghost: "bg-transparent text-current hover:bg-ink-900/5",
    danger: "bg-red-800/15 text-red-300 hover:bg-red-800/25",
    outline: "border border-ink-900/20 text-current hover:border-copper theme-desk:border-paper-100/20",
  } as const;
  return (
    <button
      className={cn(
        "inline-flex items-center justify-center gap-2 rounded-none px-3 py-2 text-xs font-medium tracking-wide disabled:opacity-40",
        styles[variant],
        className,
      )}
      {...props}
    />
  );
}

export function Card({ className, children }: { className?: string; children: ReactNode }) {
  return <div className={cn("glass rounded-none p-5", className)}>{children}</div>;
}

export function Input(props: InputHTMLAttributes<HTMLInputElement>) {
  return (
    <input
      {...props}
      className={cn(
        "w-full rounded-none border border-ink-900/15 bg-paper-50 px-3 py-2 font-mono text-sm text-ink-900 outline-none focus:border-copper theme-desk:border-paper-100/15 theme-desk:bg-ink-800 theme-desk:text-paper-100",
        props.className,
      )}
    />
  );
}

export function Textarea(props: TextareaHTMLAttributes<HTMLTextAreaElement>) {
  return (
    <textarea
      {...props}
      className={cn(
        "w-full rounded-none border border-ink-900/15 bg-paper-50 px-3 py-2 text-sm outline-none focus:border-copper theme-desk:border-paper-100/15 theme-desk:bg-ink-800 theme-desk:text-paper-100",
        props.className,
      )}
    />
  );
}

export function Select(props: SelectHTMLAttributes<HTMLSelectElement>) {
  return (
    <select
      {...props}
      className={cn(
        "w-full rounded-none border border-ink-900/15 bg-paper-50 px-3 py-2 font-mono text-sm outline-none focus:border-copper theme-desk:border-paper-100/15 theme-desk:bg-ink-800 theme-desk:text-paper-100",
        props.className,
      )}
    />
  );
}

export function Label({ children }: { children: ReactNode }) {
  return <label className="mb-1 block font-mono text-[10px] uppercase tracking-ledger text-ink-400">{children}</label>;
}

export function StatCard({
  label,
  value,
  hint,
}: {
  label: string;
  value: string;
  hint?: string;
}) {
  return (
    <Card>
      <div className="font-mono text-[10px] uppercase tracking-ledger text-ink-400">{label}</div>
      <div className="mt-2 font-mono text-2xl text-copper">{value}</div>
      {hint ? <div className="mt-1 text-xs text-ink-400">{hint}</div> : null}
    </Card>
  );
}

export function Gauge({ label, value, max }: { label: string; value: number; max: number }) {
  const pct = max <= 0 ? 0 : Math.min(100, Math.round((value / max) * 100));
  return (
    <div>
      <div className="mb-1 flex justify-between font-mono text-[10px] uppercase tracking-ledger text-ink-400">
        <span>{label}</span>
        <span className="font-mono text-copper">{pct}%</span>
      </div>
      <div className="h-1.5 overflow-hidden bg-ink-900/10">
        <div className="h-full bg-copper" style={{ width: `${pct}%` }} />
      </div>
    </div>
  );
}

export function EmptyState({ title, body }: { title: string; body: string }) {
  return (
    <Card className="text-center text-sm text-ink-400">
      <div className="text-ink-900 theme-desk:text-paper-100">{title}</div>
      <p className="mt-2">{body}</p>
    </Card>
  );
}

export function ErrorState({ message }: { message: string }) {
  return <Card className="border-red-700/40 text-sm text-red-300">{message}</Card>;
}

export function TwoFAGate({
  value,
  onChange,
}: {
  value: string;
  onChange: (v: string) => void;
}) {
  return (
    <div>
      <Label>Authenticator code (2FA required)</Label>
      <Input value={value} onChange={(e) => onChange(e.target.value)} placeholder="000000" className="tracking-[0.4em]" />
    </div>
  );
}

export const FUND_SOURCES = [
  { id: "DIRECT_DEPOSIT", label: "Direct deposit (creates PSV / TV / tree / Fast Start as specified)" },
  { id: "REFERRAL_WALLET", label: "Referral wallet — does not create volume" },
  { id: "EARNINGS_WALLET", label: "Earnings wallet — does not create volume" },
  { id: "STAKING_WALLET", label: "Staking wallet — does not create volume" },
] as const;

export function DepositSourcePicker({
  value,
  onChange,
}: {
  value: string;
  onChange: (v: (typeof FUND_SOURCES)[number]["id"]) => void;
}) {
  return (
    <div className="space-y-2">
      {FUND_SOURCES.map((s) => (
        <label
          key={s.id}
          className="flex cursor-pointer items-start gap-3 border border-ink-900/10 p-3 hover:border-copper/50"
        >
          <input type="radio" name="source" checked={value === s.id} onChange={() => onChange(s.id)} />
          <span className="text-xs">{s.label}</span>
        </label>
      ))}
    </div>
  );
}
