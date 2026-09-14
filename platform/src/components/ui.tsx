"use client";

import { cn } from "@/lib/utils";
import type { ButtonHTMLAttributes, InputHTMLAttributes, ReactNode, SelectHTMLAttributes, TextareaHTMLAttributes } from "react";

export function Button({
  className,
  variant = "primary",
  ...props
}: ButtonHTMLAttributes<HTMLButtonElement> & { variant?: "primary" | "ghost" | "danger" | "outline" }) {
  const styles = {
    primary: "bg-ember text-graphite-950 hover:bg-ember-dim",
    ghost: "bg-transparent text-[#F3EFE6] hover:bg-white/5",
    danger: "bg-red-500/15 text-red-200 hover:bg-red-500/25",
    outline: "border border-white/10 bg-white/[0.03] text-[#F3EFE6] hover:border-ember/40",
  } as const;
  return (
    <button
      className={cn(
        "inline-flex items-center justify-center gap-2 rounded-sm px-3.5 py-2 text-sm font-medium disabled:opacity-40",
        styles[variant],
        className,
      )}
      {...props}
    />
  );
}

export function Card({ className, children }: { className?: string; children: ReactNode }) {
  return <div className={cn("glass rounded-sm p-5", className)}>{children}</div>;
}

export function Input(props: InputHTMLAttributes<HTMLInputElement>) {
  return (
    <input
      {...props}
      className={cn(
        "w-full rounded-sm border border-white/10 bg-graphite-900 px-3 py-2 font-mono text-sm text-[#F3EFE6] outline-none focus:border-ember/50",
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
        "w-full rounded-sm border border-white/10 bg-graphite-900 px-3 py-2 text-sm outline-none focus:border-ember/50",
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
        "w-full rounded-sm border border-white/10 bg-graphite-900 px-3 py-2 font-mono text-sm outline-none focus:border-ember/50",
        props.className,
      )}
    />
  );
}

export function Label({ children }: { children: ReactNode }) {
  return <label className="mb-1 block text-[11px] uppercase tracking-ledger text-slate-500">{children}</label>;
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
      <div className="text-[11px] uppercase tracking-ledger text-slate-500">{label}</div>
      <div className="mt-2 font-semibold text-2xl text-white">{value}</div>
      {hint ? <div className="mt-1 text-xs text-slate-500">{hint}</div> : null}
    </Card>
  );
}

export function Gauge({ label, value, max }: { label: string; value: number; max: number }) {
  const pct = max <= 0 ? 0 : Math.min(100, Math.round((value / max) * 100));
  return (
    <div>
      <div className="mb-1 flex justify-between text-[11px] uppercase tracking-ledger text-slate-500">
        <span>{label}</span>
        <span className="font-mono text-ember">{pct}%</span>
      </div>
      <div className="h-1.5 overflow-hidden rounded-sm bg-white/10">
        <div className="h-full bg-ember" style={{ width: `${pct}%` }} />
      </div>
    </div>
  );
}

export function EmptyState({ title, body }: { title: string; body: string }) {
  return (
    <Card className="text-center text-sm text-slate-400">
      <div className="text-white">{title}</div>
      <p className="mt-2">{body}</p>
    </Card>
  );
}

export function ErrorState({ message }: { message: string }) {
  return <Card className="border-red-500/30 text-sm text-red-200">{message}</Card>;
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
          className="flex cursor-pointer items-start gap-3 rounded-sm border border-white/10 p-3 hover:border-ember/40"
        >
          <input type="radio" name="source" checked={value === s.id} onChange={() => onChange(s.id)} />
          <span className="text-xs text-slate-300">{s.label}</span>
        </label>
      ))}
    </div>
  );
}
