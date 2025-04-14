/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
      "./resources/**/*.blade.php",
      "./resources/**/*.js",
      "./resources/**/*.vue",
  ],
  darkMode: 'class',
  theme: {
      extend: {
          colors: {
              'aisuki-red': '#e61c23',
              'aisuki-red-dark': '#c41017',
              'aisuki-black': '#222',
              'aisuki-yellow': '#ffeeb3',
              'theme-primary': 'var(--text-primary)',
              'theme-secondary': 'var(--text-secondary)',
              'footer-bg': 'var(--footer-bg)',
              'footer-text': 'var(--footer-text)',
          },
          backgroundImage: {
              'header-gradient': 'linear-gradient(to right, #e61c23, #d11419)',
          },
          fontFamily: {
              'brand': ['"Abril Fatface"', '"Boldonse"', 'sans-serif'],
          }
      },
  },
  plugins: [],
}