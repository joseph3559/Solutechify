import { defineStore } from 'pinia'
import { ref } from 'vue'
import { useAuthStore } from './auth'

interface Event {
  id: number
  title: string
  description: string
  venue: string
  date: string
  price: number
  max_attendees: number
  current_attendees: number
  organization_id: number
}

interface Organization {
  id: number
  name: string
  slug: string
  domain?: string
  email: string
  phone?: string
  address?: string
  logo?: string
  description?: string
  is_active: boolean
}

export const useOrganizationStore = defineStore('organization', () => {
  const organizations = ref<Organization[]>([])
  const currentOrganization = ref<Organization | null>(null)
  const events = ref<Event[]>([])
  const loading = ref(false)
  const error = ref<string | null>(null)

  const authStore = useAuthStore()

  const fetchOrganizations = async () => {
    if (!authStore.token) return

    loading.value = true
    error.value = null

    try {
      const response = await fetch('/api/organizations', {
        headers: {
          'Authorization': `Bearer ${authStore.token}`,
        },
      })

      if (!response.ok) {
        throw new Error('Failed to fetch organizations')
      }

      const data = await response.json()
      organizations.value = data
    } catch (err: any) {
      error.value = err.message
    } finally {
      loading.value = false
    }
  }

  const fetchOrganizationBySlug = async (slug: string) => {
    if (!authStore.token) return

    loading.value = true
    error.value = null

    try {
      const response = await fetch(`/api/organizations/${slug}`, {
        headers: {
          'Authorization': `Bearer ${authStore.token}`,
        },
      })

      if (!response.ok) {
        throw new Error('Failed to fetch organization')
      }

      const data = await response.json()
      currentOrganization.value = data
    } catch (err: any) {
      error.value = err.message
    } finally {
      loading.value = false
    }
  }

  const fetchOrganizationEvents = async (organizationId: number) => {
    if (!authStore.token) return

    loading.value = true
    error.value = null

    try {
      const response = await fetch(`/api/organizations/${organizationId}/events`, {
        headers: {
          'Authorization': `Bearer ${authStore.token}`,
        },
      })

      if (!response.ok) {
        throw new Error('Failed to fetch events')
      }

      const data = await response.json()
      events.value = data
    } catch (err: any) {
      error.value = err.message
    } finally {
      loading.value = false
    }
  }

  const createEvent = async (organizationId: number, eventData: Omit<Event, 'id' | 'organization_id' | 'current_attendees'>) => {
    if (!authStore.token) return

    loading.value = true
    error.value = null

    try {
      const response = await fetch(`/api/organizations/${organizationId}/events`, {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${authStore.token}`,
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(eventData),
      })

      if (!response.ok) {
        throw new Error('Failed to create event')
      }

      const data = await response.json()
      events.value.push(data)
      return data
    } catch (err: any) {
      error.value = err.message
      throw err
    } finally {
      loading.value = false
    }
  }

  const createOrganization = async (data: { name: string; description?: string }) => {
    if (!authStore.token) return

    loading.value = true
    try {
      const response = await fetch('/api/organizations', {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${authStore.token}`,
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
      })

      if (!response.ok) {
        throw new Error('Failed to create organization')
      }

      const newOrg = await response.json()
      organizations.value.push(newOrg)
      return newOrg
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Failed to create organization'
      throw err
    } finally {
      loading.value = false
    }
  }

  const registerForEvent = async (organizationSlug: string, eventId: number, data: { name: string; email: string; phone: string }) => {
    loading.value = true
    try {
      const response = await fetch('/api/organizations/' + organizationSlug + '/events/' + eventId + '/register', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
      })

      if (!response.ok) {
        throw new Error('Failed to register for event')
      }

      return await response.json()
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Failed to register for event'
      throw err
    } finally {
      loading.value = false
    }
  }

  return {
    organizations,
    currentOrganization,
    events,
    loading,
    error,
    fetchOrganizations,
    fetchOrganizationBySlug,
    fetchOrganizationEvents,
    createEvent,
    createOrganization,
    registerForEvent,
  }
}) 