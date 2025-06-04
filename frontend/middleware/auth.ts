import { useAuthStore } from '~/stores/auth'

export default defineNuxtRouteMiddleware(async (to) => {
  const authStore = useAuthStore()
  const publicRoutes = ['/', '/login', '/register', '/auth', '/events']

  // Allow access to public routes and event detail pages
  if (publicRoutes.includes(to.path) || to.path.startsWith('/events/')) {
    return
  }

  // Initialize auth state if not already done (important for page refresh)
  if (process.client && !authStore.isAuthenticated) {
    await authStore.initAuth()
  }

  // Check if user is authenticated
  if (!authStore.isAuthenticated) {
    return navigateTo('/auth')
  }

  // For admin routes, check if user is admin
  if (to.path.startsWith('/admin') && !authStore.isAdmin) {
    return navigateTo('/')
  }
}) 