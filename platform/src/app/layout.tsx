import type { Metadata } from "next";
import { Inter, IBM_Plex_Mono } from "next/font/google";
import "./globals.css";
import { TRPCProvider } from "@/trpc/client";
import { Toaster } from "sonner";

const sans = Inter({
  subsets: ["latin"],
  variable: "--font-sans",
});

const mono = IBM_Plex_Mono({
  subsets: ["latin"],
  weight: ["400", "500"],
  variable: "--font-mono",
});

export const metadata: Metadata = {
  title: "Qorvex AI — Patent Pending market-neutral infrastructure",
  description:
    "DEX + CEX spot arbitrage sold as a monthly software license plus a 12-month portfolio. Daily credits are capped (“up to”), never a guaranteed return.",
};

export default function RootLayout({ children }: { children: React.ReactNode }) {
  return (
    <html lang="en" className={`dark ${sans.variable} ${mono.variable}`}>
      <body className="font-sans antialiased bg-navy-950 text-slate-100">
        <TRPCProvider>
          {children}
          <Toaster theme="dark" />
        </TRPCProvider>
      </body>
    </html>
  );
}
