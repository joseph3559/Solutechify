import { defineStore } from 'pinia'
import { navigateTo } from 'nuxt/app'
import type { User, AuthState, LoginResponse, RegisterData } from '@/types'
import { useAuthService } from '~/services/auth'
import { useNotification } from '~/composables/useNotification'

const redirectAfterAuth = async (user: User, isRegistration = false) => {
  console.log('Redirecting user after auth:', { user, isRegistration })
  
  // Small delay to ensure state is updated and notifications are shown
  await new Promise(resolve => setTimeout(resolve, 100))
  
  // Determine the appropriate dashboard based on user role and context
  let targetPath = '/user/dashboard' // Default fallback for regular users
  
  if (user.role === 'admin' || user.role === 'organizer' || user.role === 'super_admin') {
    targetPath = '/admin/dashboard'
  } else {
    targetPath = '/user/dashboard'
  }
  
  console.log('Navigating to:', targetPath)
  await navigateTo(targetPath)
}

export const useAuthStore = defineStore('auth', {
  state: (): AuthState => ({
    user: null,
    token: null,
    isAuthenticated: false,
    loading: false,
    error: null
  }),

  getters: {
    currentUser: (state) => state.user,
    isAdmin: (state) => state.user ? ['admin', 'super_admin', 'organizer'].includes(state.user.role) : false,
    userOrganization: (state) => state.user?.organization
  },

  actions: {
    setAuth(token: string, user: User) {
      this.token = token
      this.user = user
      this.isAuthenticated = true
      
      // Store in localStorage for persistence
      if (process.client) {
        localStorage.setItem('auth_token', token)
        localStorage.setItem('auth_user', JSON.stringify(user))
      }
    },

    clearAuth() {
      this.token = null
      this.user = null
      this.isAuthenticated = false
      
      // Clear localStorage
      if (process.client) {
        localStorage.removeItem('auth_token')
        localStorage.removeItem('auth_user')
      }
    },

    async initAuth() {
      if (process.client) {
        const token = localStorage.getItem('auth_token')
        const userStr = localStorage.getItem('auth_user')
        
        if (token && userStr) {
          try {
            const user = JSON.parse(userStr)
            this.setAuth(token, user)
            
            // Verify token is still valid
            await this.getCurrentUser()
          } catch (error) {
            this.clearAuth()
          }
        }
      }
    },

    async login(email: string, password: string) {
      this.loading = true
      this.error = null
      const authService = useAuthService()
      const { success, error } = useNotification()
      
      try {
        const response = await authService.login({ email, password })
        this.setAuth(response.token, response.user)
        
        // Show success message
        success(
          'Welcome back!', 
          `Successfully logged in as ${response.user.name}`,
          { duration: 3000 }
        )
        
        // Redirect to appropriate dashboard after setting loading to false
        this.loading = false
        await redirectAfterAuth(response.user, false)
        
        return response
        
      } catch (err: any) {
        this.error = err.message || 'Failed to login'
        
        // Show error message
        error(
          'Login Failed', 
          err.message || 'Please check your credentials and try again.',
          { duration: 6000 }
        )
        
        this.loading = false
        throw err
      }
    },

    async register(data: RegisterData) {
      this.loading = true
      this.error = null
      const authService = useAuthService()
      const { success, error } = useNotification()
      
      try {
        const response = await authService.register(data)
        this.setAuth(response.token, response.user)
        
        // Show success message
        success(
          'Account Created Successfully!', 
          `Welcome to Solutechify Events, ${response.user.name}! Your account is ready to use.`,
          { duration: 5000 }
        )
        
        // Redirect to appropriate dashboard after setting loading to false
        this.loading = false
        await redirectAfterAuth(response.user, true)
        
        return response
        
      } catch (err: any) {
        this.error = err.message || 'Failed to register'
        
        // Show error message
        error(
          'Registration Failed', 
          err.message || 'Please check your information and try again.',
          { duration: 6000 }
        )
        
        this.loading = false
        throw err
      }
    },

    async logout() {
      const authService = useAuthService()
      const { success } = useNotification()
      
      try {
        await authService.logout()
        
        // Show success message
        success(
          'Logged Out Successfully', 
          'You have been safely logged out.',
          { duration: 3000 }
        )
      } catch (error) {
        console.error('Logout error:', error)
      } finally {
        this.clearAuth()
        await navigateTo('/auth')
      }
    },

    async getCurrentUser() {
      const authService = useAuthService()
      try {
        const user = await authService.getCurrentUser()
        if (user) {
          this.user = user
        } else {
          this.clearAuth()
        }
        return user
      } catch {
        this.clearAuth()
        return null
      }
    }
  }
}) 