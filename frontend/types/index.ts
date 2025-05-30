export interface User {
  id: number
  name: string
  email: string
  role: string
  organization_id?: number
  organization?: Organization
  phone?: string
}

export interface Organization {
  id: number
  name: string
  slug: string
  description?: string
  email?: string
  logo_url?: string
  website?: string
  is_active: boolean
}

export interface Event {
  id: number
  organization_id: number
  title: string
  description: string
  venue: string
  date: string
  price: number
  max_attendees: number
  current_attendees: number
  is_active: boolean
  created_at: string
  updated_at: string
}

export interface Attendee {
  id: number
  event_id: number
  name: string
  email: string
  phone: string
  status: 'registered' | 'confirmed' | 'cancelled'
  created_at: string
  updated_at: string
}

export interface AuthState {
  user: User | null
  token: string | null
  isAuthenticated: boolean
  loading: boolean
  error: string | null
}

export interface LoginResponse {
  token: string
  user: User
}

export interface RegisterData {
  name: string
  email: string
  password: string
  organization_id?: number
  organization_name?: string
  organization_email?: string
  role?: string
  phone?: string
}

export interface AuthResponse {
  token: string
  user: User
}

export interface ApiResponse<T> {
  data?: T
  message?: string
  error?: string
}

export interface EventRegistrationData {
  name: string
  email: string
  phone: string
}

export interface EventFormData {
  title: string
  description: string
  venue: string
  date: string
  price: number
  max_attendees: number
} 