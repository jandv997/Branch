import { cn } from "@/lib/utils";

/** Geometric Q: two markets converging through a routing gap. */
export function QMark({ className, size = 32 }: { className?: string; size?: number }) {
  return (
    <svg
      width={size}
      height={size}
      viewBox="0 0 32 32"
      className={cn("shrink-0", className)}
      aria-hidden
    >
      <rect x="1" y="1" width="30" height="30" rx="3" fill="#111218" stroke="rgba(243,239,230,0.16)" />
      <path
        d="M16 7.5c-4.7 0-8.5 3.6-8.5 8.2 0 4.6 3.8 8.3 8.5 8.3 1.6 0 3.1-.4 4.3-1.1"
        fill="none"
        stroke="#F3EFE6"
        strokeWidth="1.6"
      />
      <path d="M20.2 19.4 L24.8 24.2" stroke="#FF5A36" strokeWidth="1.8" strokeLinecap="square" />
      <circle cx="11.5" cy="15.6" r="1.15" fill="#FF5A36" />
      <circle cx="20.5" cy="15.6" r="1.15" fill="#F3EFE6" />
    </svg>
  );
}

export function SimBadge({ children = "Simulation — not live venue data" }: { children?: string }) {
  return <span className="sim-badge font-mono">{children}</span>;
}
