import type { Config } from "tailwindcss";

const config: Config = {
  darkMode: ["class"],
  content: ["./src/**/*.{ts,tsx}"],
  theme: {
    extend: {
      colors: {
        paper: {
          50: "#FBF7EE",
          100: "#F3EDE1",
          200: "#E8DFD0",
          300: "#D9CBB6",
          400: "#C4B39A",
        },
        ink: {
          950: "#100E0C",
          900: "#161310",
          800: "#1F1A16",
          700: "#2A241E",
          600: "#3D342C",
          500: "#5C534A",
          400: "#7A7168",
          300: "#A39A90",
          200: "#C9C1B6",
          100: "#E8E0D4",
        },
        copper: {
          DEFAULT: "#C4622D",
          dim: "#9A4A22",
          bright: "#E07A3D",
          ink: "#3D1F12",
        },
        // Alias so existing `text-cyan` / `bg-cyan` map to copper, not Quantum Scalp teal.
        cyan: {
          DEFAULT: "#C4622D",
          dim: "#9A4A22",
          glow: "rgba(196, 98, 45, 0.16)",
        },
        violet: {
          DEFAULT: "#8A5A3C",
          dim: "#6B4030",
          glow: "rgba(138, 90, 60, 0.14)",
        },
        graphite: {
          950: "#14110E",
          900: "#1C1713",
          850: "#221C17",
          800: "#2A241E",
          700: "#3D342C",
          600: "#5C534A",
          500: "#7A7168",
          400: "#A39A90",
          300: "#C9C1B6",
        },
      },
      fontFamily: {
        sans: ["var(--font-sans)", "system-ui", "sans-serif"],
        display: ["var(--font-display)", "Georgia", "serif"],
        mono: ["var(--font-mono)", "ui-monospace", "monospace"],
      },
      boxShadow: {
        stamp: "3px 3px 0 rgba(22,19,16,0.08)",
        desk: "inset 0 1px 0 rgba(244,239,228,0.04)",
      },
      letterSpacing: {
        ledger: "0.22em",
      },
    },
  },
  plugins: [
    function themeDeskVariant({ addVariant }: { addVariant: (name: string, value: string) => void }) {
      addVariant("theme-desk", ".theme-desk &");
    },
  ],
};

export default config;
