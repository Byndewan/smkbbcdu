/*
=========================================
|THEME: Floating Layers (BBC Pay Admin) |
|PRIMARY: oklab(55.91% 0.20543 0.09128) |
|AUTHOR: abyandev657@gmail.com          |
=========================================
*/

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./app/Modules/**/*.blade.php",
  ],
  darkMode: 'class',
  theme: {
    extend: {
      fontFamily: {
        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
      },
      colors: {
        bbc: {
          50: '#fff1f2',
          100: '#ffe4e6',
          500: 'var(--bbc-primary)',
          600: 'var(--bbc-primary)',
          700: '#be123c',
        }
      },
      boxShadow: {
        'clay': 'inset 6px 6px 12px rgba(255, 255, 255, 0.4), inset -6px -6px 12px rgba(0, 0, 0, 0.05), 8px 8px 20px rgba(0, 0, 0, 0.1)',
        'clay-hover': 'inset 4px 4px 8px rgba(255, 255, 255, 0.4), inset -4px -4px 8px rgba(0, 0, 0, 0.05), 4px 4px 12px rgba(0, 0, 0, 0.1)',
        'clay-dark': 'inset 3px 3px 6px rgba(255, 255, 255, 0.05), inset -3px -3px 6px rgba(0, 0, 0, 0.3), 5px 5px 15px rgba(0, 0, 0, 0.5)',
      }
    },
  },
  plugins: [],
}
