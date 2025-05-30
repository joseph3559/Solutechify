import { useAuthStore } from '~/stores/auth'

export default defineNuxtRouteMiddleware((to) => {
  const authStore = useAuthStore()
  const publicRoutes = ['/', '/login', '/register', '/auth']

  // Allow access to public routes
  if (publicRoutes.includes(to.path)) {
    return
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