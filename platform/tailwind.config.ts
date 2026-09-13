import type { Config } from "tailwindcss";

const config: Config = {
  darkMode: ["class"],
  content: ["./src/**/*.{ts,tsx}"],
  theme: {
    extend: {
      colors: {
        navy: {
          950: "#0A0B14",
          900: "#10111C",
          850: "#12131F",
          800: "#181A28",
          700: "#252638",
        },
        cyan: {
          DEFAULT: "#22D3EE",
          dim: "#06B6D4",
          glow: "rgba(34, 211, 238, 0.16)",
        },
        violet: {
          DEFAULT: "#818CF8",
          dim: "#8B5CF6",
          glow: "rgba(139, 92, 246, 0.16)",
        },
        graphite: {
          950: "#0A0B14",
          900: "#10111C",
          850: "#12131F",
          800: "#181A28",
          700: "#252638",
          600: "#3D4158",
          500: "#6B728C",
          400: "#9CA3B8",
          300: "#CBD5E1",
        },
      },
      fontFamily: {
        sans: ["var(--font-sans)", "system-ui", "sans-serif"],
        display: ["var(--font-sans)", "system-ui", "sans-serif"],
        mono: ["var(--font-mono)", "ui-monospace", "monospace"],
      },
      boxShadow: {
        glow: "0 0 40px rgba(34, 211, 238, 0.12)",
      },
      letterSpacing: {
        ledger: "0.16em",
      },
      borderRadius: {
        xl: "0.9rem",
        "2xl": "1rem",
      },
    },
  },
  plugins: [],
};

export default config;
