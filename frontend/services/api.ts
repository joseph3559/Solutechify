import axios from 'axios'

// Create a function to initialize the API with runtime config
export function createApi() {
  const config = useRuntimeConfig()
  return axios.create({
    baseURL: config.public.apiBase,
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    }
  })
}

// Export a composable to use the API
export function useApi() {
  const api = createApi()

  // Add request interceptor
  api.interceptors.request.use(
    (config) => {
      const token = localStorage.getItem('auth_token')
      if (token) {
        config.headers.Authorization = `Bearer ${token}`
      }
      return config
    },
    (error) => {
      return Promise.reject(error)
    }
  )

  // Add response interceptor
  api.interceptors.response.use(
    (response) => response,
    (error) => {
      if (error.response?.status === 401) {
        localStorage.removeItem('auth_token')
        localStorage.removeItem('auth_user')
        window.location.href = '/auth'
      }
      return Promise.reject(error)
    }
  )

  return api
} 