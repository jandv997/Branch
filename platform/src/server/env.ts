import { z } from "zod";

const EnvSchema = z.object({
  NODE_ENV: z.enum(["development", "test", "production"]).default("development"),
  APP_URL: z.string().default("http://localhost:3000"),
  APP_NAME: z.string().default("Qorvex AI"),
  DATABASE_URL: z.string().default("postgresql://qorvex:qorvex@localhost:5432/qorvex?schema=public"),
  REDIS_URL: z.string().default("redis://localhost:6379"),
  AUTH_SECRET: z.string().default("dev-auth-secret-change-me-please-32chars"),
  SESSION_TTL_SECONDS: z.coerce.number().default(86400),
  SEED_PASSWORD: z.string().default("Qorvex!demo2026"),
  ADMIN_IP_ALLOWLIST: z.string().default(""),
  KYC_UPLOAD_DIR: z.string().default("./uploads/kyc"),
  KYC_WITHDRAW_THRESHOLD_CENTS: z.coerce.number().default(100_000),
  PAYMENT_ADAPTER: z.enum(["dev", "stripe", "crypto"]).default("dev"),
  ENGINE_DEFAULT_BPS: z.coerce.number().default(40),
  HALT_CREDITS: z.coerce.boolean().default(false),
  HALT_WITHDRAWS: z.coerce.boolean().default(false),
  S3_ENDPOINT: z.string().optional().default(""),
  S3_BUCKET: z.string().optional().default(""),
  S3_ACCESS_KEY: z.string().optional().default(""),
  S3_SECRET_KEY: z.string().optional().default(""),
  S3_REGION: z.string().optional().default("auto"),
});

export type Env = z.infer<typeof EnvSchema>;

let cached: Env | null = null;

export function env(): Env {
  if (cached) return cached;
  const parsed = EnvSchema.safeParse(process.env);
  if (!parsed.success) {
    const missing = parsed.error.issues.map((i) => i.path.join(".")).join(", ");
    throw new Error(`Invalid environment: ${missing}`);
  }
  cached = parsed.data;
  return cached;
}

export function adminIpAllowed(ip: string | null): boolean {
  const list = env().ADMIN_IP_ALLOWLIST.split(",").map((s) => s.trim()).filter(Boolean);
  if (list.length === 0) return true;
  if (!ip) return false;
  return list.includes(ip);
}
