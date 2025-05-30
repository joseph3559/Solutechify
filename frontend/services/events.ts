import { useApi } from './api'
import type { Event, EventRegistrationData } from '@/types'

export function useEventsService() {
  const api = useApi()

  return {
    async getEvents() {
      const response = await api.get<Event[]>('/events')
    return response.data
  },

    async getEvent(id: number) {
      const response = await api.get<Event>(`/events/${id}`)
    return response.data
  },

    async createEvent(data: Partial<Event>) {
      const response = await api.post<Event>('/events', data)
    return response.data
  },

    async updateEvent(id: number, data: Partial<Event>) {
      const response = await api.put<Event>(`/events/${id}`, data)
    return response.data
  },

    async deleteEvent(id: number) {
      await api.delete(`/events/${id}`)
  },

    async registerForEvent(eventId: number, data: EventRegistrationData) {
      const response = await api.post(`/events/${eventId}/register`, data)
    return response.data
    }
  }
} 