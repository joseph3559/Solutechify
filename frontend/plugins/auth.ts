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
    // Allow access to home page sections
    if (to.path === '/' || to.hash.startsWith('#')) {
      return true
    }
    
    // Check if route is a public route (no authentication required)
    const isPublicRoute = (
      to.path === '/' ||
      to.path === '/login' ||
      to.path === '/register' ||
      to.path.startsWith('/admin/login') ||
      // Organization pages (public)
      to.path.match(/^\/[^\/]+$/) ||  // /{organization}
      // Event pages (public)
      to.path.match(/^\/[^\/]+\/events$/) ||  // /{organization}/events
      to.path.match(/^\/[^\/]+\/events\/\d+$/)  // /{organization}/events/{id}
    )
    
    if (isPublicRoute) {
      // Redirect to home if already authenticated and trying to access login/register
      if (authStore.isAuthenticated && ['/login', '/register'].includes(to.path)) {
        return '/'
      }
      return true
    }

    // For admin routes, check authentication
    if (to.path.startsWith('/admin') && to.path !== '/admin/login') {
      if (!authStore.isAuthenticated) {
        return `/admin/login?redirect=${encodeURIComponent(to.fullPath)}`
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