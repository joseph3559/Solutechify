import { defineStore } from 'pinia'
import { authService } from '~/services/auth.service'

interface User {
  id: number
  name: string
  email: string
  phone?: string
}

interface AuthState {
  user: User | null
  token: string | null
  isAuthenticated: boolean
}

export const useAuthStore = defineStore('auth', {
  state: (): AuthState => ({
    user: null,
    token: null,
    isAuthenticated: false,
  }),

  getters: {
    currentUser: (state) => state.user,
  },

  actions: {
    async login(email: string, password: string) {
      try {
        const response = await authService.login(email, password)
        this.token = response.token
        this.user = response.user
        this.isAuthenticated = true
        if (process.client) {
          localStorage.setItem('token', response.token)
        }
        return response
      } catch (error) {
        throw error
      }
    },

    async register(name: string, email: string, password: string, password_confirmation: string) {
      try {
        const response = await authService.register(name, email, password, password_confirmation)
        this.token = response.token
        this.user = response.user
        this.isAuthenticated = true
        if (process.client) {
          localStorage.setItem('token', response.token)
        }
        return response
      } catch (error) {
        throw error
      }
    },

    async fetchUser() {
      try {
        const user = await authService.getUser()
        this.user = user
        this.isAuthenticated = true
        return user
      } catch (error) {
        this.logout()
        throw error
      }
    },

    logout() {
      this.user = null
      this.token = null
      this.isAuthenticated = false
      if (process.client) {
        localStorage.removeItem('token')
      }
    },

    initAuth() {
      if (process.client) {
        const token = localStorage.getItem('token')
        if (token) {
          this.token = token
          this.fetchUser()
        }
      }
    },
  },
}) 