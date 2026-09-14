import { CompConfigSchema, DEFAULT_COMP_CONFIG, type CompConfig } from "@/domain/comp/config";
import { prisma } from "./db";

const CONFIG_ID = "singleton";

export async function getCompConfig(): Promise<CompConfig> {
  const row = await prisma.compConfigRow.findUnique({ where: { id: CONFIG_ID } });
  if (!row) return DEFAULT_COMP_CONFIG;
  return CompConfigSchema.parse(row.json);
}

export async function putCompConfig(json: unknown, updatedBy?: string): Promise<CompConfig> {
  const parsed = CompConfigSchema.parse(json);
  await prisma.compConfigRow.upsert({
    where: { id: CONFIG_ID },
    create: { id: CONFIG_ID, json: parsed, updatedBy },
    update: { json: parsed, updatedBy },
  });
  return parsed;
}

export async function ensureSystemRows() {
  await prisma.compConfigRow.upsert({
    where: { id: CONFIG_ID },
    create: { id: CONFIG_ID, json: DEFAULT_COMP_CONFIG },
    update: {},
  });
  await prisma.systemHalt.upsert({
    where: { id: CONFIG_ID },
    create: { id: CONFIG_ID, haltCredits: false, haltWithdraws: false },
    update: {},
  });
}
