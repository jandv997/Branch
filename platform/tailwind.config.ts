import type { Config } from "tailwindcss";

const config: Config = {
  darkMode: ["class"],
  content: ["./src/**/*.{ts,tsx}"],
  theme: {
    extend: {
      colors: {
        graphite: {
          950: "#07080C",
          900: "#0B0D12",
          850: "#10131A",
          800: "#161A22",
          700: "#1E2430",
          600: "#2A3344",
          500: "#3D4A5C",
          400: "#6B7789",
          300: "#9AA6B8",
        },
        cyan: {
          DEFAULT: "#22D3EE",
          dim: "#0891B2",
          glow: "rgba(34, 211, 238, 0.18)",
        },
        violet: {
          DEFAULT: "#8B5CF6",
          dim: "#6D28D9",
          glow: "rgba(139, 92, 246, 0.16)",
        },
      },
      fontFamily: {
        sans: ["var(--font-sans)", "system-ui", "sans-serif"],
        mono: ["var(--font-mono)", "ui-monospace", "monospace"],
      },
      boxShadow: {
        glass: "0 0 0 1px rgba(255,255,255,0.06), 0 20px 50px rgba(0,0,0,0.45)",
        glow: "0 0 40px rgba(34,211,238,0.12)",
      },
      backgroundImage: {
        grid: "linear-gradient(rgba(255,255,255,0.04) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.04) 1px, transparent 1px)",
      },
    },
  },
  plugins: [],
};

export default config;
