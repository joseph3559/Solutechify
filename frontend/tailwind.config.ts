import type { Config } from 'tailwindcss'
import defaultTheme from 'tailwindcss/defaultTheme' // Import defaultTheme for fonts
import colors from 'tailwindcss/colors' // Import Tailwind's colors for easy reference

export default {
  content: [
    './components/**/*.{js,vue,ts}',
    './layouts/**/*.vue',
    './pages/**/*.vue',
    './plugins/**/*.{js,ts}',
    './app.vue',
    './error.vue'
  ],
  darkMode: 'class', // or 'media' if you prefer OS-level dark mode
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#2563eb', // Your current primary (blue-600)
          '50': '#EFF6FF',  // blue-50
          '100': '#DBEAFE', // blue-100
          '200': '#BFDBFE', // blue-200
          '300': '#93C5FD', // blue-300
          '400': '#60A5FA', // blue-400
          '500': '#3B82F6', // blue-500
          '600': '#2563EB', // blue-600 (same as DEFAULT)
          '700': '#1D4ED8', // blue-700
          '800': '#1E40AF', // blue-800
          '900': '#1E3A8A', // blue-900
          '950': '#172554', // blue-950
        },
        secondary: {
          DEFAULT: '#64748b', // Your current secondary (slate-500)
          '50': colors.slate[50],
          '100': colors.slate[100],
          '200': colors.slate[200],
          '300': colors.slate[300],
          '400': colors.slate[400],
          '500': colors.slate[500], // DEFAULT
          '600': colors.slate[600],
          '700': colors.slate[700],
          '800': colors.slate[800],
          '900': colors.slate[900],
          '950': colors.slate[950],
        },
        // You can add other custom colors here
      },
      fontFamily: {
        // To make the site look more professional, consider adding a custom font family.
        // 'sans' will be the default. Inter is a great professional choice.
        sans: ['Inter var', ...defaultTheme.fontFamily.sans],
        // You can add other font families like 'serif' or custom ones here
        // display: ['Poppins', ...defaultTheme.fontFamily.sans], // Example for headings
      },
      // You can extend other theme properties like spacing, borderRadius, etc.
      // Example for custom animation keyframes used in the landing page
      animation: {
        'float': 'float 8s infinite ease-in-out',
        'float-delayed': 'float-delayed 10s infinite ease-in-out',
        'fade-in': 'fadeIn 0.8s ease-out forwards',
        'fade-in-up': 'fadeInUp 0.8s ease-out forwards',
      },
      keyframes: {
        float: {
          '0%, 100%': { transform: 'translateY(0) scale(1)', opacity: '0.6' },
          '50%': { transform: 'translateY(-20px) scale(1.05)', opacity: '0.4' },
        },
        'float-delayed': {
          '0%, 100%': { transform: 'translateY(0) scale(1)', opacity: '0.5' },
          '50%': { transform: 'translateY(20px) scale(1.03)', opacity: '0.3' },
        },
        fadeIn: {
          from: { opacity: '0', transform: 'translateY(20px)' },
          to: { opacity: '1', transform: 'translateY(0)' },
        },
        fadeInUp: {
          from: { opacity: '0', transform: 'translateY(40px)' },
          to: { opacity: '1', transform: 'translateY(0)' },
        },
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'), // Added for prose styling
    // You can add other plugins like @tailwindcss/aspect-ratio if needed
  ],
} satisfies Config
