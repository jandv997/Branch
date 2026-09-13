import { NextRequest, NextResponse } from "next/server";
import { getSessionUser, SESSION_COOKIE } from "@/server/auth";
import { prisma } from "@/server/db";

export async function GET(req: NextRequest) {
  const token = req.cookies.get(SESSION_COOKIE)?.value;
  const user = await getSessionUser(token);
  if (!user) return NextResponse.json({ error: "UNAUTHORIZED" }, { status: 401 });
  const rows = await prisma.ledgerEntry.findMany({
    where: { userId: user.id },
    orderBy: { createdAt: "desc" },
    take: 5000,
  });
  const header = "id,createdAt,wallet,direction,amountCents,type,refType,refId";
  const lines = rows.map((r) =>
    [r.id, r.createdAt.toISOString(), r.wallet, r.direction, r.amountCents.toString(), r.type, r.refType ?? "", r.refId ?? ""].join(","),
  );
  const csv = [header, ...lines].join("\n");
  return new NextResponse(csv, {
    headers: {
      "Content-Type": "text/csv; charset=utf-8",
      "Content-Disposition": "attachment; filename=qorvex-ledger.csv",
    },
  });
}
