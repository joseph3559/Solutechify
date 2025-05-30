import axios from 'axios'

// Create a function to initialize the API with runtime config
export function createApi() {
  const config = useRuntimeConfig()
  return axios.create({
    baseURL: config.public.apiBase,
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    },
    timeout: 10000 // 10 second timeout
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
      console.error('Request Error:', error)
      return Promise.reject({
        message: 'Failed to send request. Please check your internet connection.'
      })
    }
  )

  // Add response interceptor
  api.interceptors.response.use(
    (response) => response,
    (error) => {
      console.error('API Error:', error)

      // Handle network errors
      if (!error.response) {
        return Promise.reject({
          message: error.message === 'Network Error' 
            ? 'Unable to connect to the server. Please check your internet connection.'
            : error.message === 'timeout of 10000ms exceeded'
            ? 'Request timed out. Please try again.'
            : 'An unexpected error occurred. Please try again.'
        })
      }

      // Handle authentication errors
      if (error.response.status === 401) {
        localStorage.removeItem('auth_token')
        localStorage.removeItem('auth_user')
        window.location.href = '/admin/login?error=session_expired'
        return Promise.reject({
          message: 'Your session has expired. Please log in again.'
        })
      }

      // Handle other common HTTP errors
      const errorMessages: Record<number, string> = {
        400: 'Invalid request. Please check your input.',
        403: 'You do not have permission to perform this action.',
        404: 'The requested resource was not found.',
        422: 'Validation failed. Please check your input.',
        500: 'Server error. Our team has been notified.',
        503: 'Service temporarily unavailable. Please try again later.'
      }

      const status = error.response.status as keyof typeof errorMessages
      const message = error.response.data?.message || 
                     errorMessages[status] || 
                     'An unexpected error occurred. Please try again.'

      return Promise.reject({
        status: error.response.status,
        message,
        errors: error.response.data?.errors || null
      })
    }
  )

  return api
} 