/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './index.html',
    './src/**/*.{js,ts,jsx,tsx}',
  ],
  theme: {
    extend: {
      colors: {
        'secondary': '#50B2D4',
        'deeper': '#113846',
        'darker': '#257A97',
        'lighter-5': '#dcf0f6',
        'lighter-20': '#89C5DA',
        'lighter-40': '#B9E6F2',
        'headings': '#03035A',
        'error': '#FF4954',
        'error-text': '#4C1619',
        'error-container': '#FFB6BB',
        'warning': '#F2994A',
        'warning-container': '#FFE399',
        'warning-text': '#805D00',
        'success': '#8EBF26',
        'information': '#21AA93',
      },
      boxShadow: {
        'home': '0 12px 16px rgba(0,0,0,.04), 0 4px 56px rgba(0,0,0,.04)',
      },
    },
  },
  plugins: [
    require('tailwindcss-animate'),
  ],
};