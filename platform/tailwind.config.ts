import type { Config } from "tailwindcss";

const config: Config = {
  darkMode: ["class"],
  content: ["./src/**/*.{ts,tsx}"],
  theme: {
    extend: {
      colors: {
        graphite: {
          950: "#0B0C0F",
          900: "#111218",
          850: "#16171E",
          800: "#1C1D26",
          700: "#2A2C36",
          600: "#3E414D",
          500: "#6B6F7A",
          400: "#8B909A",
          300: "#C4C7CE",
        },
        ember: {
          DEFAULT: "#FF5A36",
          dim: "#D64522",
          glow: "rgba(255, 90, 54, 0.18)",
        },
        // Existing `text-cyan` / `bg-cyan` map to ember — not Quantum Scalp teal, not Lovable cyan.
        cyan: {
          DEFAULT: "#FF5A36",
          dim: "#D64522",
          glow: "rgba(255, 90, 54, 0.18)",
        },
        navy: {
          950: "#0B0C0F",
          900: "#111218",
          850: "#16171E",
          800: "#1C1D26",
          700: "#2A2C36",
        },
        violet: {
          DEFAULT: "#FF5A36",
          dim: "#D64522",
          glow: "rgba(255, 90, 54, 0.12)",
        },
      },
      fontFamily: {
        sans: ["var(--font-sans)", "system-ui", "sans-serif"],
        display: ["var(--font-display)", "system-ui", "sans-serif"],
        mono: ["var(--font-mono)", "ui-monospace", "monospace"],
      },
      letterSpacing: {
        ledger: "0.14em",
      },
    },
  },
  plugins: [],
};

export default config;
