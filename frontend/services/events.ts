import api from './api'

export interface Event {
  id: number
  title: string
  description: string
  date: string
  venue: string
  price: number
  maxAttendees: number
  registeredAttendees: number
  image?: string
  organizationId?: number
}

export interface EventRegistration {
  name: string
  email: string
  phone: string
}

// Public endpoints that don't require authentication
const publicApi = api.create({
  baseURL: process.env.NUXT_PUBLIC_API_BASE || 'http://localhost:8000/api'
})

export const eventService = {
  // Public methods that don't require authentication
  async getPublicEvents(organizationId: string | number) {
    const response = await publicApi.get(`/organizations/${organizationId}/events`)
    return response.data
  },

  async getPublicEventDetails(organizationId: string | number, eventId: string | number) {
    const response = await publicApi.get(`/organizations/${organizationId}/events/${eventId}`)
    return response.data
  },

  // Protected methods that require authentication
  async registerForEvent(organizationId: string | number, eventId: string | number, registrationData: any) {
    const response = await api.post(`/organizations/${organizationId}/events/${eventId}/register`, registrationData)
    return response.data
  },

  async createEvent(organizationId: string | number, eventData: Partial<Event>) {
    const response = await api.post(`/organizations/${organizationId}/events`, eventData)
    return response.data
  },

  async updateEvent(organizationId: string | number, eventId: string | number, eventData: Partial<Event>) {
    const response = await api.put(`/organizations/${organizationId}/events/${eventId}`, eventData)
    return response.data
  },

  async deleteEvent(organizationId: string | number, eventId: string | number) {
    const response = await api.delete(`/organizations/${organizationId}/events/${eventId}`)
    return response.data
  },

  // Get all events for a tenant
  async getEvents(tenant: string) {
    const response = await api.get<Event[]>(`/${tenant}/events`)
    return response.data
  },

  // Get featured events for a tenant
  async getFeaturedEvents(tenant: string) {
    const response = await api.get<Event[]>(`/${tenant}/events/featured`)
    return response.data
  },

  // Get a single event
  async getEvent(tenant: string, eventId: string | number) {
    const response = await api.get<Event>(`/${tenant}/events/${eventId}`)
    return response.data
  },

  // Get event statistics
  async getEventStats(tenant: string) {
    const response = await api.get(`/${tenant}/events/stats`)
    return response.data
  }
} 