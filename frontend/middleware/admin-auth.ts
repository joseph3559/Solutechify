import { useAuthStore } from '@/stores/auth'
import { defineNuxtRouteMiddleware, navigateTo } from 'nuxt/app'
import type { RouteLocationNormalized } from 'vue-router'
import { createError } from 'h3'

export default defineNuxtRouteMiddleware((to) => {
  const authStore = useAuthStore()
  
  // Initialize auth state
  authStore.initAuth()
  
  // Only apply authentication checks to admin routes
  if (!to.path.startsWith('/admin')) {
    return
  }
  
  // Check if user is authenticated for admin routes
  if (!authStore.isAuthenticated) {
    return navigateTo('/admin/login')
  }
  
  // Check if user is admin (for admin routes)
  if (!authStore.isAdmin) {
    throw createError({
      statusCode: 403,
      statusMessage: 'Access denied'
    })
  }
})
