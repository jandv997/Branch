"use client";

import { UserBoLayout } from "@/components/bo-layout";
import type { ReactNode } from "react";

export default function Layout({ children }: { children: ReactNode }) {
  return <UserBoLayout>{children}</UserBoLayout>;
}
