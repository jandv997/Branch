import type { Metadata } from "next";
import { IBM_Plex_Sans, IBM_Plex_Mono, Syne } from "next/font/google";
import "./globals.css";
import { TRPCProvider } from "@/trpc/client";
import { Toaster } from "sonner";

const display = Syne({
  subsets: ["latin"],
  weight: ["500", "600", "700"],
  variable: "--font-display",
});

const sans = IBM_Plex_Sans({
  subsets: ["latin"],
  weight: ["400", "500", "600"],
  variable: "--font-sans",
});

const mono = IBM_Plex_Mono({
  subsets: ["latin"],
  weight: ["400", "500"],
  variable: "--font-mono",
});

export const metadata: Metadata = {
  title: "Qorvex AI — Market-Neutral Intelligence",
  description:
    "AI-powered market-neutral trading infrastructure. Software license for DEX + CEX spot-arbitrage routing. Daily credits are caps (“up to”), never a guaranteed return.",
};

export default function RootLayout({ children }: { children: React.ReactNode }) {
  return (
    <html lang="en" className={`dark ${display.variable} ${sans.variable} ${mono.variable}`}>
      <body className="font-sans antialiased bg-graphite-950 text-[#F8FAFE]">
        <TRPCProvider>
          {children}
          <Toaster theme="dark" />
        </TRPCProvider>
      </body>
    </html>
  );
}
