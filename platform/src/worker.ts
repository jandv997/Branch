import "dotenv/config";
import { Queue, Worker } from "bullmq";
import IORedis from "ioredis";
import { JOBS } from "./jobs/runner";

const connection = new IORedis(process.env.REDIS_URL ?? "redis://localhost:6379", {
  maxRetriesPerRequest: null,
});

export const jobQueue = new Queue("qorvex-jobs", { connection });

async function schedule() {
  await jobQueue.add("dailyCredits", {}, { repeat: { pattern: "5 0 * * *" }, jobId: "repeat-dailyCredits" });
  await jobQueue.add("weeklySalary", {}, { repeat: { pattern: "10 0 * * 1" }, jobId: "repeat-weeklySalary" });
  await jobQueue.add("monthlyRanks", {}, { repeat: { pattern: "20 0 1 * *" }, jobId: "repeat-monthlyRanks" });
  await jobQueue.add("fastStartClose", {}, { repeat: { pattern: "15 0 * * *" }, jobId: "repeat-fastStartClose" });
  await jobQueue.add("licenseExpire", {}, { repeat: { pattern: "30 0 * * *" }, jobId: "repeat-licenseExpire" });
  await jobQueue.add("withdrawExpire", {}, { repeat: { pattern: "40 0 * * *" }, jobId: "repeat-withdrawExpire" });
}

const worker = new Worker(
  "qorvex-jobs",
  async (job) => {
    const name = job.name as keyof typeof JOBS;
    const fn = JOBS[name];
    if (!fn) throw new Error(`Unknown job ${job.name}`);
    return fn();
  },
  { connection, concurrency: Number(process.env.WORKER_CONCURRENCY ?? 5) },
);

worker.on("completed", (job) => {
  console.log(`[worker] ${job.name} completed`);
});
worker.on("failed", (job, err) => {
  console.error(`[worker] ${job?.name} failed`, err);
});

schedule()
  .then(() => console.log("[worker] Qorvex jobs scheduled"))
  .catch((err) => {
    console.error(err);
    process.exit(1);
  });
