import { useAuthStore } from '@/stores/auth'
import { defineNuxtPlugin } from 'nuxt/app'

export default defineNuxtPlugin(async () => {
  const authStore = useAuthStore()

  // Initialize authentication state on app start
  if (process.client) {
    await authStore.initAuth()
  }
}) 