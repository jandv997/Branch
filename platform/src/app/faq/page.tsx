import { PublicChrome, PatentBadge } from "@/components/brand";
import { FaqList } from "@/components/faq-list";

export default function FaqPage() {
  return (
    <PublicChrome>
      <main className="mx-auto max-w-3xl px-5 py-16">
        <PatentBadge />
        <h1 className="mt-4 font-display text-4xl tracking-tight">FAQ</h1>
        <FaqList />
      </main>
    </PublicChrome>
  );
}
