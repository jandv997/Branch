import type { FundingSource } from "./config";

/**
 * Canonical event kinds the compensation engine understands.
 * UI never invents payouts — every credit path maps to one of these.
 */
export type CompEventKind =
  | "NEW_PORTFOLIO_DIRECT_DEPOSIT"
  | "TOP_UP_DIRECT_DEPOSIT"
  | "WALLET_FUNDED_NEW"
  | "WALLET_FUNDED_TOP_UP"
  | "LICENSE_FIRST"
  | "LICENSE_RENEWAL"
  | "DAILY_CREDIT"
  | "STAKING_MOVE"
  | "EARNINGS_MOVE"
  | "REFERRAL_MOVE";

export const WALLET_SOURCES: FundingSource[] = [
  "REFERRAL_WALLET",
  "EARNINGS_WALLET",
  "STAKING_WALLET",
];

export function classifyFunding(source: FundingSource, isNewPortfolio: boolean): CompEventKind {
  if (source === "DIRECT_DEPOSIT") {
    return isNewPortfolio ? "NEW_PORTFOLIO_DIRECT_DEPOSIT" : "TOP_UP_DIRECT_DEPOSIT";
  }
  return isNewPortfolio ? "WALLET_FUNDED_NEW" : "WALLET_FUNDED_TOP_UP";
}

/** Tree (L1–L3) pays only on NEW PORTFOLIO + DIRECT_DEPOSIT, and once on first-ever license fee. */
export function treePaysOn(kind: CompEventKind): boolean {
  return kind === "NEW_PORTFOLIO_DIRECT_DEPOSIT" || kind === "LICENSE_FIRST";
}

/** PSV/TV: L1 (PSV) and L1–L7 (TV) DIRECT_DEPOSIT only — new portfolios AND DD top-ups. */
export function createsDirectDepositVolume(kind: CompEventKind): boolean {
  return kind === "NEW_PORTFOLIO_DIRECT_DEPOSIT" || kind === "TOP_UP_DIRECT_DEPOSIT";
}

/** Fast Start considers DIRECT_DEPOSIT amounts (new + top-up). Wallets / licenses / credits never. */
export function fastStartConsiders(kind: CompEventKind): boolean {
  return kind === "NEW_PORTFOLIO_DIRECT_DEPOSIT" || kind === "TOP_UP_DIRECT_DEPOSIT";
}

export type WhyNotPaidCode =
  | "PAID"
  | "TOP_UP"
  | "WALLET_SOURCE"
  | "LICENSE_RENEWAL"
  | "DAILY_CREDIT"
  | "NOT_TREE_EVENT"
  | "NO_UPLINE"
  | "INACTIVE_COMPRESSION_SKIPPED"
  | "USER_FROZEN"
  | "SELF"
  | "WASH";

export function whyTreeNotPaid(kind: CompEventKind): WhyNotPaidCode | null {
  if (treePaysOn(kind)) return null;
  switch (kind) {
    case "TOP_UP_DIRECT_DEPOSIT":
      return "TOP_UP";
    case "WALLET_FUNDED_NEW":
    case "WALLET_FUNDED_TOP_UP":
    case "STAKING_MOVE":
    case "EARNINGS_MOVE":
    case "REFERRAL_MOVE":
      return "WALLET_SOURCE";
    case "LICENSE_RENEWAL":
      return "LICENSE_RENEWAL";
    case "DAILY_CREDIT":
      return "DAILY_CREDIT";
    default:
      return "NOT_TREE_EVENT";
  }
}

export const WHY_NOT_PAID_COPY: Record<WhyNotPaidCode, string> = {
  PAID: "Tree commission paid on this event.",
  TOP_UP: "Top-ups (including DIRECT_DEPOSIT top-ups) do not pay tree commission.",
  WALLET_SOURCE:
    "Funding from referral, earnings, or staking wallets never pays tree commission and never creates PSV, TV, or Fast Start.",
  LICENSE_RENEWAL: "License renewals do not pay tree commission. Only the first-ever license fee does.",
  DAILY_CREDIT: "Daily engine credits do not pay tree commission.",
  NOT_TREE_EVENT: "This event is not a tree-paying event.",
  NO_UPLINE: "No upline at this compressed pay level.",
  INACTIVE_COMPRESSION_SKIPPED: "Inactive upline skipped for PAY (compression). Volume still flowed.",
  USER_FROZEN: "Earner account is frozen or closed.",
  SELF: "Self-referrals are excluded.",
  WASH: "Wash / circular funding is excluded.",
};
