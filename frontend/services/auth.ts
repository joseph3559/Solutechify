import type { LoginResponse, RegisterData, AuthResponse } from '@/types'
import { useApi } from './api'

interface LoginCredentials {
  email: string
  password: string
}

export function useAuthService() {
  const api = useApi()

  return {
  async login(credentials: LoginCredentials): Promise<AuthResponse> {
    const response = await api.post<AuthResponse>('/auth/login', credentials)
    this.setToken(response.data.token)
    return response.data
  },

  async register(data: RegisterData): Promise<AuthResponse> {
    const response = await api.post<AuthResponse>('/auth/register', data)
    this.setToken(response.data.token)
    return response.data
  },

  async logout(): Promise<void> {
    try {
      await api.post('/auth/logout')
    } finally {
      this.removeToken()
    }
  },

  async getCurrentUser(): Promise<AuthResponse['user'] | null> {
    try {
      const response = await api.get<AuthResponse>('/auth/me')
      return response.data.user
    } catch {
      return null
    }
  },

  setToken(token: string): void {
    if (typeof window !== 'undefined') {
      localStorage.setItem('auth_token', token)
    }
  },

  getToken(): string | null {
    if (typeof window !== 'undefined') {
      return localStorage.getItem('auth_token')
    }
    return null
  },

  removeToken(): void {
    if (typeof window !== 'undefined') {
      localStorage.removeItem('auth_token')
    }
  },

  isAuthenticated(): boolean {
    return !!this.getToken()
    }
  }
} 