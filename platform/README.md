# Qorvex AI

Patent-pending market-neutral AI trading infrastructure: **DEX + CEX spot arbitrage** sold as a monthly **software license** plus a **12-month portfolio**.

Daily percentages are **caps (“up to”)**, never a guaranteed ROI. The UI never invents payouts — every credit is computed in `src/domain/comp/*` and posted as an immutable ledger row plus an audit row.

## Stack

- Next.js App Router + TypeScript + Tailwind
- tRPC + Zod (shared with the policy module)
- Prisma + PostgreSQL
- Redis (sessions, 2FA setup, locks, BullMQ)
- BullMQ worker (`src/worker.ts`)
- Integer **USD cents** (`bigint`) everywhere — no untyped money math (`src/domain/money.ts`)

## Layout

| Path | What |
| --- | --- |
| `src/domain/comp/` | Compensation source of truth (tree, Fast Start, PSV/TV, ranks, daily caps, 25% clip) |
| `src/server/` | Ledger, auth, funding, tRPC |
| `src/jobs/` | Idempotent jobs + CLI |
| `src/app/` | Public site, user BO (`/app`), admin (`/admin`) |
| `prisma/` | Schema, migrations, seed |

**Rank salary + one-time rank bonuses post to `AVAILABLE`** (documented in `CompConfig.rankPayoutWallet`). Tree + Fast Start → `REFERRAL`. Daily credits → `EARNINGS`.

## Run locally

```bash
cp .env.example .env
docker compose up -d postgres redis
npx prisma migrate deploy
npx tsx prisma/seed.ts
npm run dev          # web → http://localhost:3000
npm run worker       # BullMQ worker (repeatable cron)
```

Or all-in-one:

```bash
docker compose up --build
```

### Manual jobs (idempotent, replayable)

```bash
npm run job:daily
npm run job:weekly
npm run job:monthly
npm run job:fast-start
npm run job:license-expire
npm run job:withdraw-expire
```

Each run writes `job_runs` + `job_exceptions`.

## Seed accounts

Password: `SEED_PASSWORD` (default `Qorvex!demo2026`)

- `superadmin@qorvex.local` — SUPERADMIN
- `finance@qorvex.local` — FINANCE
- `support@qorvex.local` — SUPPORT
- `demo.l0@qorvex.local` … `demo.l7@qorvex.local` — 7-level tree with mixed DIRECT_DEPOSIT / EARNINGS / STAKING / REFERRAL funding

## Tests

```bash
npm test
```

Required suites:

- Tree pay / no-pay matrix (DD new vs top-up vs wallet)
- Fast Start paths A/B, one-earner, 25% clip
- PSV L1 vs TV L1–L7 vs L8 ignored
- Rank reset + spillover
- Inactive compression on PAY only
- Integration: fund → ledger → wallet → withdraw happy path

## Money rules (short)

- Fast Start + 3-level tree on a deposit can **never exceed 25%** of that deposit.
- Staking / referral / earnings wallet funding **never** creates PSV, TV, tree, or Fast Start.
- Only **one** user is paid Fast Start on any single deposit (genealogical L1 sponsor).
- Compression skips inactive uplines for **PAY** only. Volume always flows.

## Security

- 2FA mandatory before first withdraw
- Whitelist address 24h delay
- KYC gate above `kycWithdrawThresholdCents`
- Admin IP allowlist: `ADMIN_IP_ALLOWLIST`
- Helmet-style headers in `next.config.ts`
- Payment adapter interface: `src/server/payments.ts` (`dev` simulates license pay + DD)

## Patent Pending

Shown on the public site, license cards, user back-office footer, and admin footer.
