import { z } from "zod";
import { getCompConfig } from "../../comp-config";
import { prisma } from "../../db";
import { publicProcedure, router } from "../init";

export const publicRouter = router({
  config: publicProcedure.query(async () => {
    const cfg = await getCompConfig();
    return {
      patentPending: cfg.patentPending,
      copy: cfg.copy,
      licenses: cfg.licenses,
      ranks: cfg.ranks,
      tree: cfg.tree,
      depositCompCapBps: cfg.depositCompCapBps,
      fastStart: cfg.fastStart,
      engineDefaultBps: cfg.engineDefaultBps,
      rankPayoutWallet: cfg.rankPayoutWallet,
      stakingLockDays: cfg.stakingLockDays,
      whitelistDelayHours: cfg.whitelistDelayHours,
    };
  }),
  cms: publicProcedure.input(z.object({ slug: z.string() })).query(async ({ input }) => {
    return prisma.cmsPage.findUnique({ where: { slug: input.slug } });
  }),
  faqs: publicProcedure.query(async () => {
    const page = await prisma.cmsPage.findUnique({ where: { slug: "faq" } });
    return page?.body ?? [
      {
        q: "What exactly am I buying?",
        a: "A monthly software license plus optional access to a 12-month funded portfolio. License fees buy software access. They are not an investment product.",
      },
      {
        q: "Is the daily percentage a guaranteed return?",
        a: "No. Credits are capped (“up to”) at the lesser of the engine rate and the license daily cap. They are not a guaranteed return. Actual credits depend on engine performance and may be zero.",
      },
      {
        q: "What happens if my license lapses?",
        a: "An inactive or lapsed license credits zero for that day. Portfolio principal remains on the ledger; daily credits resume only while a live license is in force.",
      },
      {
        q: "Do I need a team to use the platform?",
        a: "No. A passive license holder can buy a license, fund a portfolio by direct deposit, receive daily credits up to cap, and withdraw weekly with zero team.",
      },
      {
        q: "Which funding sources create referral volume?",
        a: "Only DIRECT_DEPOSIT funding creates PSV, TV, tree commission, or Fast Start. Referral, earnings, and staking wallet funding never create volume.",
      },
      {
        q: "When does the referral tree pay?",
        a: "On a new portfolio funded by direct deposit, and once on a first-ever license fee. It does not pay on top-ups, wallet-funded portfolios, renewals, or daily credits.",
      },
      {
        q: "How much can be paid out on a single deposit?",
        a: "Tree plus Fast Start never exceeds 25% of that deposit. Fast Start has a separate raw cap and only one earner per deposit.",
      },
      {
        q: "What is Patent Pending?",
        a: "Qorvex AI market-neutral infrastructure is patent pending. The public site, license cards, user back office, and admin footers display this status.",
      },
    ];
  }),
});
