import { PageIntro, PublicChrome } from "@/components/brand";
import { FaqList } from "@/components/faq-list";

export default function FaqPage() {
  return (
    <PublicChrome>
      <PageIntro
        kicker="FAQ"
        title="Questions worth answering precisely"
        body="Where an answer touches a payout rule, it restates the policy rather than paraphrasing it."
      />
      <main className="mx-auto max-w-3xl px-5 pb-20">
        <FaqList />
      </main>
    </PublicChrome>
  );
}
