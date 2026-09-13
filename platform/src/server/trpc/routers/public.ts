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
        q: "Is the daily credit guaranteed?",
        a: "No. Credits are capped (“up to”) at the lesser of the engine rate and the license daily cap. They are not a guaranteed return.",
      },
      {
        q: "Do wallet-funded portfolios create team volume?",
        a: "No. Referral, earnings, and staking wallet funding never create PSV, TV, tree commission, or Fast Start.",
      },
      {
        q: "What is Patent Pending?",
        a: "Qorvex AI market-neutral infrastructure is patent pending. The public site, license cards, user back office, and admin footers display this status.",
      },
    ];
  }),
});
