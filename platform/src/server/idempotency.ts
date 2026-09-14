import { prisma } from "./db";

export async function withIdempotency<T extends object>(opts: {
  key: string;
  userId?: string;
  route: string;
  fn: () => Promise<T>;
}): Promise<T> {
  const existing = await prisma.idempotencyKey.findUnique({ where: { key: opts.key } });
  if (existing) return existing.response as T;

  const result = await opts.fn();
  const expiresAt = new Date(Date.now() + 24 * 60 * 60 * 1000);
  try {
    await prisma.idempotencyKey.create({
      data: {
        key: opts.key,
        userId: opts.userId,
        route: opts.route,
        status: 200,
        response: JSON.parse(
          JSON.stringify(result, (_k, v) => (typeof v === "bigint" ? v.toString() : v)),
        ),
        expiresAt,
      },
    });
  } catch {
    const raced = await prisma.idempotencyKey.findUnique({ where: { key: opts.key } });
    if (raced) return raced.response as T;
  }
  return result;
}
