import Redis from "ioredis";

const globalForRedis = globalThis as unknown as { redis?: Redis };

export function redis(): Redis {
  if (!globalForRedis.redis) {
    globalForRedis.redis = new Redis(process.env.REDIS_URL ?? "redis://localhost:6379", {
      maxRetriesPerRequest: null,
      enableReadyCheck: true,
    });
  }
  return globalForRedis.redis;
}

export async function withLock<T>(key: string, ttlMs: number, fn: () => Promise<T>): Promise<T> {
  const token = `${Date.now()}-${Math.random()}`;
  const ok = await redis().set(`lock:${key}`, token, "PX", ttlMs, "NX");
  if (ok !== "OK") throw new Error(`LOCK_HELD:${key}`);
  try {
    return await fn();
  } finally {
    const cur = await redis().get(`lock:${key}`);
    if (cur === token) await redis().del(`lock:${key}`);
  }
}
