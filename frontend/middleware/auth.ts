import { useAuthStore } from '@/stores/auth'
import { defineNuxtRouteMiddleware, navigateTo } from 'nuxt/app'
import type { RouteLocationNormalized } from 'vue-router'

export default defineNuxtRouteMiddleware((to) => {
  const authStore = useAuthStore()
  
  // Public routes that don't require authentication
  const publicRoutes = [
    '/',
    '/login',
    '/register',
    '/forgot-password',
    '/reset-password',
    '/contact',
    '/events'
  ]

  // Allow access to home page sections
  if (to.path === '/' || to.hash.startsWith('#')) {
    return
  }

  // Allow access to event pages without authentication
  if (to.path.includes('/events')) {
    return
  }

  // Check if route is public
  if (publicRoutes.includes(to.path)) {
    return
  }

  // Check if the route requires authentication
  if (to.path.startsWith('/dashboard') || to.path.startsWith('/organizations')) {
    if (!authStore.token) {
      return navigateTo('/login')
    }
  }

  // Check if user is already authenticated and trying to access login/register
  if (authStore.token && (to.path === '/login' || to.path === '/register')) {
    return navigateTo('/dashboard')
  }

  // Skip middleware if route doesn't require auth
  if (to.meta.auth === false) return

  // If user is not authenticated and trying to access a protected route
  if (!authStore.isAuthenticated) {
    return navigateTo('/login')
  }
})
