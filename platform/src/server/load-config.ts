import { getCompConfig } from "@/server/comp-config";
import { DEFAULT_COMP_CONFIG, type CompConfig } from "@/domain/comp/config";

export async function loadConfig(): Promise<CompConfig> {
  if (process.env.NEXT_PHASE === "phase-production-build") return DEFAULT_COMP_CONFIG;
  try {
    return await getCompConfig();
  } catch {
    return DEFAULT_COMP_CONFIG;
  }
}
