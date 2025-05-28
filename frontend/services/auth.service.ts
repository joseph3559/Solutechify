import { useFetch } from 'nuxt/app'

interface User {
  id: number
  name: string
  email: string
  phone?: string
}

interface AuthResponse {
  token: string
  user: User
}

const API_URL = 'http://localhost:8000/api'

export const authService = {
  async login(email: string, password: string): Promise<AuthResponse> {
    try {
      const { data, error } = await useFetch<AuthResponse>(`${API_URL}/login`, {
        method: 'POST',
        body: { email, password },
      })
      if (error.value) throw error.value
      return data.value as AuthResponse
    } catch (error: any) {
      throw error.data || error.message
    }
  },

  async register(name: string, email: string, password: string, password_confirmation: string): Promise<AuthResponse> {
    try {
      const { data, error } = await useFetch<AuthResponse>(`${API_URL}/register`, {
        method: 'POST',
        body: { name, email, password, password_confirmation },
      })
      if (error.value) throw error.value
      return data.value as AuthResponse
    } catch (error: any) {
      throw error.data || error.message
    }
  },

  async getUser(): Promise<User> {
    try {
      const { data, error } = await useFetch<User>(`${API_URL}/user`, {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('token')}`,
        },
      })
      if (error.value) throw error.value
      return data.value as User
    } catch (error: any) {
      throw error.data || error.message
    }
  },

  logout() {
    localStorage.removeItem('token')
  },
} 