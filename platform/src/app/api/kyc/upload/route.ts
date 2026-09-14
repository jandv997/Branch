import { NextRequest, NextResponse } from "next/server";
import { mkdir, writeFile } from "node:fs/promises";
import path from "node:path";
import { getSessionUser, SESSION_COOKIE } from "@/server/auth";
import { prisma } from "@/server/db";
import { randomToken } from "@/server/crypto";

export async function POST(req: NextRequest) {
  const token = req.cookies.get(SESSION_COOKIE)?.value;
  const user = await getSessionUser(token);
  if (!user) return NextResponse.json({ error: "UNAUTHORIZED" }, { status: 401 });
  const form = await req.formData();
  const file = form.get("file");
  const kind = String(form.get("kind") ?? "id");
  if (!(file instanceof File)) return NextResponse.json({ error: "file required" }, { status: 400 });
  const buf = Buffer.from(await file.arrayBuffer());
  const dir = process.env.KYC_UPLOAD_DIR ?? "./uploads/kyc";
  await mkdir(dir, { recursive: true });
  const key = `${user.id}-${randomToken(8)}-${file.name.replace(/[^a-zA-Z0-9._-]/g, "_")}`;
  await writeFile(path.join(dir, key), buf);
  const doc = await prisma.kycDoc.create({
    data: { userId: user.id, kind, storageKey: key, status: "PENDING" },
  });
  await prisma.user.update({ where: { id: user.id }, data: { kycStatus: "PENDING" } });
  return NextResponse.json({ id: doc.id, status: doc.status });
}
