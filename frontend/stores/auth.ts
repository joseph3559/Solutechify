import { defineStore } from 'pinia'
import type { User, AuthState, LoginResponse, RegisterData } from '@/types'
import { useAuthService } from '~/services/auth'

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
    isAdmin: (state) => state.user?.role === 'admin',
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
      
      try {
        const response = await authService.login({ email, password })
        this.setAuth(response.token, response.user)
        return response
      } catch (error: any) {
        this.error = error.message || 'Failed to login'
        throw error
      } finally {
        this.loading = false
      }
    },

    async register(data: RegisterData) {
      this.loading = true
      this.error = null
      const authService = useAuthService()
      
      try {
        const response = await authService.register(data)
        this.setAuth(response.token, response.user)
        return response
      } catch (error: any) {
        this.error = error.message || 'Failed to register'
        throw error
      } finally {
        this.loading = false
      }
    },

    async logout() {
      const authService = useAuthService()
      try {
        await authService.logout()
      } finally {
        this.clearAuth()
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