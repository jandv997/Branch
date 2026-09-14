/** Configurable compliance strip — copy comes from CompConfig, never hardcoded ROI claims. */
export function Disclosure({ children }: { children: string }) {
  return (
    <p className="border border-white/[0.08] bg-graphite-900 px-4 py-3 text-[12px] leading-relaxed text-graphite-400">
      {children}
    </p>
  );
}
