import { SiteFooter, SiteHeader, PatentBadge } from "@/components/brand";
import { FaqList } from "@/components/faq-list";

export default function FaqPage() {
  return (
    <div className="min-h-screen">
      <SiteHeader />
      <main className="mx-auto max-w-3xl px-5 py-16">
        <PatentBadge />
        <h1 className="mt-4 text-4xl">FAQ</h1>
        <FaqList />
      </main>
      <SiteFooter />
    </div>
  );
}
