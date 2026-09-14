import { router } from "./init";
import { adminRouter } from "./routers/admin";
import { authRouter } from "./routers/auth";
import { publicRouter } from "./routers/public";
import { userRouter } from "./routers/user";

export const appRouter = router({
  auth: authRouter,
  public: publicRouter,
  user: userRouter,
  admin: adminRouter,
});

export type AppRouter = typeof appRouter;
