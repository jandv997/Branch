export type ActivityKind =
  | "CREDIT"
  | "WALLET"
  | "TEAM"
  | "RANK"
  | "FUNDING"
  | "WITHDRAW"
  | "DEPOSIT"
  | "SYSTEM"
  | "JOB"
  | "AUDIT";

export type ActivityItem = {
  id: string;
  at: Date;
  kind: ActivityKind;
  title: string;
  detail: string;
  amountCents?: bigint;
  href?: string;
};

export function ledgerKind(type: string): ActivityKind {
  if (type.includes("DAILY_CREDIT")) return "CREDIT";
  if (type.includes("TREE") || type.includes("FAST_START")) return "TEAM";
  if (type.includes("RANK") || type.includes("SALARY")) return "RANK";
  if (type.includes("PORTFOLIO") || type.includes("FUND")) return "FUNDING";
  if (type.includes("WITHDRAW")) return "WITHDRAW";
  if (type.includes("DEPOSIT")) return "DEPOSIT";
  if (type.includes("STAKING")) return "WALLET";
  return "WALLET";
}

export function mergeActivity(items: ActivityItem[], take = 50): ActivityItem[] {
  return items.sort((a, b) => b.at.getTime() - a.at.getTime()).slice(0, take);
}
