// https://nuxt.com/docs/api/configuration/nuxt-config
import { defineNuxtConfig } from 'nuxt/config'

export default defineNuxtConfig({
  ssr: true,
  modules: [
    '@pinia/nuxt',
    '@nuxtjs/tailwindcss',
    '@nuxtjs/color-mode'
  ],
  tailwindcss: {
    cssPath: '~/assets/css/tailwind.css',
    configPath: 'tailwind.config.ts',
  },
  app: {
    head: {
      title: 'SolutechifyEvents',
      meta: [
        { charset: 'utf-8' },
        { name: 'viewport', content: 'width=device-width, initial-scale=1' },
        { name: 'description', content: 'Event Management System' }
      ],
    }
  },
  runtimeConfig: {
    public: {
      apiBase: process.env.API_BASE_URL || 'http://localhost:8000/api'
    }
  },
  typescript: {
    strict: true,
    typeCheck: true,
    tsConfig: {
      compilerOptions: {
        paths: {
          '#imports': ['./.nuxt/types/imports.d.ts']
        }
      }
    }
  },
  devtools: { enabled: true },
  nitro: {
    compressPublicAssets: true,
  },
  experimental: {
    componentIslands: true,
  },
  imports: {
    dirs: ['stores']
  },
  // middleware: {
  //   global: ['auth']
  // },
  compatibilityDate: '2025-05-28',
  colorMode: {
    classSuffix: '',
    preference: 'system',
    fallback: 'light'
  },
  css: [
    '@/assets/css/main.css'
  ],
  build: {
    transpile: ['@heroicons/vue']
  },
  pinia: {},
})
