import "dotenv/config";
import { JOBS, type JobName } from "./runner";

async function main() {
  const name = process.argv[2] as JobName | undefined;
  if (!name || !(name in JOBS)) {
    console.error(`Usage: tsx src/jobs/cli.ts <${Object.keys(JOBS).join("|")}>`);
    process.exit(1);
  }
  const result = await JOBS[name]();
  console.log(JSON.stringify(result, (_k, v) => (typeof v === "bigint" ? v.toString() : v), 2));
}

main().catch((err) => {
  console.error(err);
  process.exit(1);
});
