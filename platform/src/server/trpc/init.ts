import { initTRPC, TRPCError } from "@trpc/server";
import superjson from "superjson";
import { ZodError } from "zod";
import { adminIpAllowed } from "../env";
import { getSessionUser, isFinance, isStaff, isSuper, type SessionUser } from "../auth";
import { SESSION_COOKIE } from "../auth";

function readCookie(header: string | null, name: string): string | undefined {
  if (!header) return undefined;
  const parts = header.split(";").map((p) => p.trim());
  const hit = parts.find((p) => p.startsWith(`${name}=`));
  return hit?.slice(name.length + 1);
}

export async function createTRPCContext(opts: { headers: Headers }) {
  const token = readCookie(opts.headers.get("cookie"), SESSION_COOKIE);
  const user = await getSessionUser(token);
  const ip =
    opts.headers.get("x-forwarded-for")?.split(",")[0]?.trim() ??
    opts.headers.get("x-real-ip") ??
    null;
  return {
    user,
    token,
    ip,
    userAgent: opts.headers.get("user-agent"),
    idempotencyKey: opts.headers.get("idempotency-key"),
  };
}

export type TRPCContext = Awaited<ReturnType<typeof createTRPCContext>>;

const t = initTRPC.context<TRPCContext>().create({
  transformer: superjson,
  errorFormatter({ shape, error }) {
    return {
      ...shape,
      data: {
        ...shape.data,
        zodError: error.cause instanceof ZodError ? error.cause.flatten() : null,
      },
    };
  },
});

export const router = t.router;
export const publicProcedure = t.procedure;

const authed = t.middleware(({ ctx, next }) => {
  if (!ctx.user) throw new TRPCError({ code: "UNAUTHORIZED" });
  if (ctx.user.status === "FROZEN") throw new TRPCError({ code: "FORBIDDEN", message: "Account frozen" });
  return next({ ctx: { ...ctx, user: ctx.user } });
});

export const protectedProcedure = t.procedure.use(authed);

export const staffProcedure = protectedProcedure.use(({ ctx, next }) => {
  if (!isStaff(ctx.user.role)) throw new TRPCError({ code: "FORBIDDEN" });
  if (!adminIpAllowed(ctx.ip)) throw new TRPCError({ code: "FORBIDDEN", message: "Admin IP not allowlisted" });
  return next({ ctx });
});

export const financeProcedure = protectedProcedure.use(({ ctx, next }) => {
  if (!isFinance(ctx.user.role)) throw new TRPCError({ code: "FORBIDDEN" });
  if (!adminIpAllowed(ctx.ip)) throw new TRPCError({ code: "FORBIDDEN", message: "Admin IP not allowlisted" });
  return next({ ctx });
});

export const superProcedure = protectedProcedure.use(({ ctx, next }) => {
  if (!isSuper(ctx.user.role)) throw new TRPCError({ code: "FORBIDDEN" });
  if (!adminIpAllowed(ctx.ip)) throw new TRPCError({ code: "FORBIDDEN", message: "Admin IP not allowlisted" });
  return next({ ctx });
});

export function requireTotp(user: SessionUser) {
  if (!user.totpEnabled) {
    throw new TRPCError({ code: "FORBIDDEN", message: "2FA required" });
  }
}
