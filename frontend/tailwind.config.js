/** @type {import('tailwindcss').Config} */
export default {
  content: ['./index.html', './src/**/*.{ts,tsx}'],
  // Dark mode is toggled via a class on <html>
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        // SafePulse brand palette (inspired by health-tech blues/teals)
        primary: {
          50:  '#eef9ff',
          100: '#d8f1ff',
          200: '#b9e8ff',
          300: '#87daff',
          400: '#4dc3fe',
          500: '#22a8f5',
          600: '#0d8ce0',
          700: '#0d72b5',
          800: '#125f94',
          900: '#144f7a',
          950: '#0d3353',
        },
        accent: {
          400: '#34d399', // teal-green
          500: '#10b981',
          600: '#059669',
        },
        danger: {
          400: '#f87171',
          500: '#ef4444',
          600: '#dc2626',
        },
        warning: {
          400: '#fbbf24',
          500: '#f59e0b',
        },
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', 'sans-serif'],
      },
    },
  },
  plugins: [],
};
