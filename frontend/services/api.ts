import axios from 'axios'

const api = axios.create({
  baseURL: process.env.NUXT_PUBLIC_API_BASE || 'http://localhost:8000/api',
  headers: {
    'Content-Type': 'application/json'
  }
})

// Request interceptor for API calls
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

// Response interceptor for API calls
api.interceptors.response.use(
  (response) => response,
  async (error) => {
    const originalRequest = error.config

    // Handle 401 Unauthorized errors
    if (error.response?.status === 401 && !originalRequest._retry) {
      originalRequest._retry = true
      
      // TODO: Implement refresh token logic here
      // const token = await refreshToken()
      // localStorage.setItem('auth_token', token)
      // originalRequest.headers.Authorization = `Bearer ${token}`
      // return api(originalRequest)
    }

    return Promise.reject(error)
  }
)

export default api 