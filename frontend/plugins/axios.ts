import axios from 'axios'
import { defineNuxtPlugin, useRuntimeConfig } from 'nuxt/app'
import { useAuthStore } from '@/stores/auth'
import { useAuthService } from '@/services/auth'

export default defineNuxtPlugin((nuxtApp) => {
  const config = useRuntimeConfig()
  const authStore = useAuthStore()
  const authService = useAuthService()

  // Create axios instance
  const api = axios.create({
    baseURL: config.public.apiBase,
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    }
  })

  // Request interceptor
  api.interceptors.request.use(
    (config) => {
      const token = authService.getToken()
      if (token) {
        config.headers.Authorization = `Bearer ${token}`
      }
      return config
    },
    (error) => {
      return Promise.reject(error)
    }
  )

  // Response interceptor
  api.interceptors.response.use(
    (response) => response,
    async (error) => {
      if (error.response?.status === 401) {
        authStore.clearAuth()
        navigateTo('/auth')
      }
      return Promise.reject(error)
    }
  )

  return {
    provide: {
      axios: api
    }
  }
})
