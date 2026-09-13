"use client";

import { AdminBoLayout } from "@/components/bo-layout";
import type { ReactNode } from "react";

export default function Layout({ children }: { children: ReactNode }) {
  return <AdminBoLayout>{children}</AdminBoLayout>;
}
