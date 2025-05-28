import { useAuthStore } from '@/stores/auth'
import { defineNuxtPlugin } from 'nuxt/app'
import { useRoute, useRouter } from 'vue-router'
import type { RouteLocationNormalized } from 'vue-router'

export default defineNuxtPlugin(async () => {
  const authStore = useAuthStore()
  const route = useRoute()
  const router = useRouter()

  // Check authentication on initial load
  await authStore.initAuth()

  // Watch for route changes
  router.beforeEach(async (to: RouteLocationNormalized) => {
    // Public routes that don't require authentication
    const publicRoutes = ['/', '/login', '/register', '/events']
    
    // Allow access to home page sections
    if (to.path === '/' || to.hash.startsWith('#')) {
      return true
    }
    
    if (publicRoutes.includes(to.path)) {
      // Redirect to home if already authenticated and trying to access login/register
      if (authStore.isAuthenticated && ['/login', '/register'].includes(to.path)) {
        return '/'
      }
      return true
    }

    // If not authenticated and trying to access a protected route, redirect to login
    if (!authStore.isAuthenticated) {
      return `/login?redirect=${encodeURIComponent(to.fullPath)}`
    }

    return true
  })
}) 