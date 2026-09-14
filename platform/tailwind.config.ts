import type { Config } from "tailwindcss";

/** Colors sampled from citiequity.com: navy #061D40 / #051B4D, CTA #347BEC, paper #F8FAFE. */
const config: Config = {
  darkMode: ["class"],
  content: ["./src/**/*.{ts,tsx}"],
  theme: {
    extend: {
      colors: {
        graphite: {
          950: "#051B4D",
          900: "#061D40",
          850: "#0A2858",
          800: "#0E3270",
          700: "#163E86",
          600: "#2A5EA0",
          500: "#6D8AB0",
          400: "#9BB4CC",
          300: "#E3F8FF",
        },
        ember: {
          DEFAULT: "#347BEC",
          dim: "#1F62D0",
          glow: "rgba(52, 123, 236, 0.18)",
        },
        cyan: {
          DEFAULT: "#347BEC",
          dim: "#1F62D0",
          glow: "rgba(52, 123, 236, 0.18)",
        },
        navy: {
          950: "#051B4D",
          900: "#061D40",
          850: "#0A2858",
          800: "#0E3270",
          700: "#163E86",
        },
        violet: {
          DEFAULT: "#347BEC",
          dim: "#1F62D0",
          glow: "rgba(52, 123, 236, 0.12)",
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
