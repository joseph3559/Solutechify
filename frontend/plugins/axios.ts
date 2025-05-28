import axios from 'axios'
import { defineNuxtPlugin, useRuntimeConfig } from 'nuxt/app'
import { useAuthStore } from '@/stores/auth'
import { authService } from '@/services/auth'

export default defineNuxtPlugin((nuxtApp) => {
  const config = useRuntimeConfig()
  
  const axiosInstance = axios.create({
    baseURL: config.public.apiBase as string,
    timeout: 10000,
    headers: {
      'Content-Type': 'application/json'
    }
  })

  // Request interceptor
  axiosInstance.interceptors.request.use(
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
  axiosInstance.interceptors.response.use(
    (response) => response,
    async (error) => {
      const authStore = useAuthStore()
      
      if (error.response?.status === 401) {
        await authStore.logout()
        return Promise.reject(new Error('Session expired. Please login again.'))
      }
      
      return Promise.reject(error.response?.data?.message || error.message || 'An error occurred')
    }
  )

  return {
    provide: {
      axios: axiosInstance
    }
  }
})
