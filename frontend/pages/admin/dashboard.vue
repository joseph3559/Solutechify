<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <!-- Left side - Brand and Navigation -->
          <div class="flex items-center space-x-8">
            <!-- Brand/Logo -->
            <div class="flex items-center">
              <NuxtLink to="/" class="flex items-center space-x-2 text-blue-600 hover:text-blue-800 transition-colors">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2L2 7v10c0 5.55 3.84 9.99 9 11 5.16-1.01 9-5.45 9-11V7l-10-5z"/>
                </svg>
                <span class="text-xl font-bold">SolutechifyEvents</span>
              </NuxtLink>
            </div>
            
            <!-- Navigation Links -->
            <div class="hidden md:flex items-center space-x-6">
              <NuxtLink 
                to="/admin/dashboard" 
                class="text-gray-900 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium border-b-2 border-blue-600"
              >
                Dashboard
              </NuxtLink>
              <NuxtLink 
                :to="`/${authStore.userOrganization?.slug}`" 
                class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition-colors"
              >
                View Public Page
              </NuxtLink>
            </div>
          </div>

          <!-- Center - Organization Name -->
          <div class="flex items-center">
            <div class="text-center">
              <h1 class="text-lg font-semibold text-gray-900">
                {{ authStore.userOrganization?.name }} Admin
              </h1>
              <p class="text-xs text-gray-500">
                Organization Management
              </p>
            </div>
          </div>

          <!-- Right side - User Menu -->
          <div class="flex items-center space-x-4">
            <!-- User Info -->
            <div class="hidden md:block text-right">
              <div class="text-sm font-medium text-gray-900">{{ authStore.currentUser?.name }}</div>
              <div class="text-xs text-gray-500">{{ authStore.currentUser?.email }}</div>
            </div>
            
            <!-- User Avatar/Icon -->
            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
              <span class="text-blue-600 font-semibold text-sm">
                {{ authStore.currentUser?.name?.charAt(0).toUpperCase() }}
              </span>
            </div>
            
            <!-- Logout Button -->
            <button
              @click="handleLogout"
              class="bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 px-3 py-2 rounded-md text-sm font-medium transition-colors"
            >
              Logout
            </button>
          </div>
        </div>
      </div>
      
      <!-- Mobile menu (optional - you can expand this later) -->
      <div class="md:hidden px-4 pb-2">
        <div class="flex flex-col space-y-2">
          <NuxtLink 
            to="/admin/dashboard" 
            class="text-gray-900 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium"
          >
            Dashboard
          </NuxtLink>
          <NuxtLink 
            :to="`/${authStore.userOrganization?.slug}`" 
            class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium"
          >
            View Public Page
          </NuxtLink>
        </div>
      </div>
    </nav>

    <!-- Breadcrumb Navigation -->
    <div class="bg-gray-50 border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center space-x-2 py-3">
          <NuxtLink to="/" class="text-blue-600 hover:text-blue-800 text-sm">
            Home
          </NuxtLink>
          <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
          </svg>
          <NuxtLink :to="`/${authStore.userOrganization?.slug}`" class="text-blue-600 hover:text-blue-800 text-sm">
            {{ authStore.userOrganization?.name }}
          </NuxtLink>
          <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
          </svg>
          <span class="text-gray-500 text-sm">Admin Dashboard</span>
        </div>
      </div>
    </div>

    <!-- Quick Actions Bar -->
    <div class="bg-white border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between py-4">
          <div class="flex items-center space-x-4">
            <h2 class="text-lg font-medium text-gray-900">Dashboard Overview</h2>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
              {{ events?.length || 0 }} Active Events
            </span>
          </div>
          <div class="flex items-center space-x-3">
            <button
              @click="showCreateModal = true"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
            >
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
              </svg>
              New Event
            </button>
            <button
              @click="refresh()"
              class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
            >
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
              </svg>
              Refresh
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <CalendarIcon class="h-6 w-6 text-gray-400" />
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Total Events</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ events?.length || 0 }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <UsersIcon class="h-6 w-6 text-gray-400" />
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Total Attendees</dt>
                  <dd class="text-lg font-medium text-gray-900">{{ totalAttendees }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <CurrencyDollarIcon class="h-6 w-6 text-gray-400" />
              </div>
              <div class="ml-5 w-0 flex-1">
                <dl>
                  <dt class="text-sm font-medium text-gray-500 truncate">Total Revenue</dt>
                  <dd class="text-lg font-medium text-gray-900">${{ totalRevenue }}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Events Management -->
      <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
          <div class="flex justify-between items-center mb-6">
            <div>
              <h3 class="text-lg leading-6 font-medium text-gray-900">Events Management</h3>
              <p class="mt-1 text-sm text-gray-500">Manage your organization's events</p>
            </div>
          </div>

          <!-- Loading State -->
          <div v-if="pending" class="flex justify-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
          </div>

          <!-- Events Table -->
          <div v-else-if="events && events.length > 0" class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Event
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Date
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Attendees
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Price
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Status
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="event in events" :key="event.id">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div>
                      <div class="text-sm font-medium text-gray-900">{{ event.title }}</div>
                      <div class="text-sm text-gray-500">{{ event.venue }}</div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ formatDate(event.date) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ event.current_attendees || 0 }} / {{ event.max_attendees }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${{ event.price }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span :class="event.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                      {{ event.is_active ? 'Active' : 'Inactive' }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                    <button
                      @click="editEvent(event)"
                      class="text-blue-600 hover:text-blue-900"
                    >
                      Edit
                    </button>
                    <button
                      @click="viewAttendees(event)"
                      class="text-green-600 hover:text-green-900"
                    >
                      Attendees
                    </button>
                    <button
                      @click="deleteEvent(event)"
                      class="text-red-600 hover:text-red-900"
                    >
                      Delete
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- No Events -->
          <div v-else class="text-center py-8">
            <CalendarIcon class="mx-auto h-12 w-12 text-gray-400" />
            <h3 class="mt-2 text-sm font-medium text-gray-900">No events</h3>
            <p class="mt-1 text-sm text-gray-500">Get started by creating your first event.</p>
          </div>
        </div>
      </div>
    </main>

    <!-- Create/Edit Event Modal -->
    <div v-if="showCreateModal || showEditModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-lg max-w-2xl w-full p-6 max-h-screen overflow-y-auto">
        <h3 class="text-lg font-semibold mb-4">
          {{ showEditModal ? 'Edit Event' : 'Create New Event' }}
        </h3>
        
        <form @submit.prevent="submitEvent">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
              <input
                v-model="eventForm.title"
                type="text"
                required
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
              <textarea
                v-model="eventForm.description"
                rows="3"
                required
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
              ></textarea>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Venue</label>
              <input
                v-model="eventForm.venue"
                type="text"
                required
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Date & Time</label>
              <input
                v-model="eventForm.date"
                type="datetime-local"
                required
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Price ($)</label>
              <input
                v-model="eventForm.price"
                type="number"
                step="0.01"
                min="0"
                required
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Max Attendees</label>
              <input
                v-model="eventForm.max_attendees"
                type="number"
                min="1"
                required
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
          </div>

          <div class="flex space-x-3 mt-6">
            <button
              type="button"
              @click="closeModals"
              class="flex-1 bg-gray-300 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-400 transition-colors"
            >
              Cancel
            </button>
            <button
              type="submit"
              :disabled="eventLoading"
              class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 disabled:bg-blue-400 transition-colors"
            >
              {{ eventLoading ? 'Saving...' : (showEditModal ? 'Update' : 'Create') }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Attendees Modal -->
    <div v-if="showAttendeesModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-lg max-w-4xl w-full p-6 max-h-screen overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-semibold">Attendees for {{ selectedEvent?.title }}</h3>
          <button @click="showAttendeesModal = false" class="text-gray-400 hover:text-gray-600">
            <XMarkIcon class="h-6 w-6" />
          </button>
        </div>
        
        <div v-if="attendees && attendees.length > 0" class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registered</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="attendee in attendees" :key="attendee.id">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ attendee.name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ attendee.email }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ attendee.phone }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ formatDate(attendee.created_at) }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div v-else class="text-center py-8 text-gray-500">
          No attendees registered yet.
        </div>
      </div>
    </div>

    <!-- Success/Error Messages -->
    <div v-if="message" class="fixed bottom-4 right-4 p-4 rounded-md shadow-lg z-50" :class="messageType === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'">
      {{ message }}
    </div>
  </div>
</template>

<script setup lang="ts">
import { CalendarIcon, UsersIcon, CurrencyDollarIcon, XMarkIcon } from '@heroicons/vue/24/outline'
import { createError } from 'h3'
import type { Event, Attendee, EventFormData } from '@/types'

// Auth middleware
definePageMeta({
  middleware: 'admin-auth'
})

const config = useRuntimeConfig()
const router = useRouter()
const authStore = useAuthStore()

// Reactive data
const showCreateModal = ref(false)
const showEditModal = ref(false)
const showAttendeesModal = ref(false)
const selectedEvent = ref<Event | null>(null)
const eventLoading = ref(false)
const message = ref('')
const messageType = ref<'success' | 'error'>('success')
const attendees = ref<Attendee[]>([])

const eventForm = ref<EventFormData>({
  title: '',
  description: '',
  venue: '',
  date: '',
  price: 0,
  max_attendees: 0
})

// Initialize auth first
await authStore.initAuth()

// Wait for auth to be initialized and check if we have organization data
if (!authStore.isAuthenticated || !authStore.userOrganization?.id) {
  throw createError({
    statusCode: 401,
    statusMessage: 'Authentication required'
  })
}

// Fetch events
const { data: events, pending, error, refresh } = await useFetch<Event[]>(`/admin/organizations/${authStore.userOrganization!.id}/events`, {
  baseURL: config.public.apiBase,
  headers: {
    Authorization: `Bearer ${authStore.token}`
  }
})

// Computed properties
const totalAttendees = computed(() => {
  return events.value?.reduce((total: number, event: Event) => total + (event.current_attendees || 0), 0) || 0
})

const totalRevenue = computed(() => {
  return events.value?.reduce((total: number, event: Event) => total + (event.price * (event.current_attendees || 0)), 0).toFixed(2) || '0.00'
})

// Methods
const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    weekday: 'short',
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const handleLogout = () => {
  authStore.logout()
  router.push('/admin/login')
}

const editEvent = (event: Event) => {
  selectedEvent.value = event
  eventForm.value = {
    title: event.title,
    description: event.description,
    venue: event.venue,
    date: event.date.split('.')[0],
    price: event.price,
    max_attendees: event.max_attendees
  }
  showEditModal.value = true
}

const deleteEvent = async (event: Event) => {
  if (!confirm('Are you sure you want to delete this event?')) return

  try {
    await $fetch(`/admin/organizations/${authStore.userOrganization!.id}/events/${event.id}`, {
      method: 'DELETE',
      baseURL: config.public.apiBase,
      headers: {
        Authorization: `Bearer ${authStore.token}`
      }
    })

    message.value = 'Event deleted successfully'
    messageType.value = 'success'
    refresh()
    
    setTimeout(() => {
      message.value = ''
    }, 3000)
  } catch (error: any) {
    message.value = error.data?.message || 'Failed to delete event'
    messageType.value = 'error'
    
    setTimeout(() => {
      message.value = ''
    }, 3000)
  }
}

const viewAttendees = async (event: Event) => {
  selectedEvent.value = event
  
  try {
    const response = await $fetch(`/admin/organizations/${authStore.userOrganization!.id}/events/${event.id}/attendees`, {
      baseURL: config.public.apiBase,
      headers: {
        Authorization: `Bearer ${authStore.token}`
      }
    })
    
    attendees.value = response as Attendee[]
    showAttendeesModal.value = true
  } catch (error: any) {
    message.value = error.data?.message || 'Failed to load attendees'
    messageType.value = 'error'
    
    setTimeout(() => {
      message.value = ''
    }, 3000)
  }
}

const submitEvent = async () => {
  eventLoading.value = true

  try {
    const url = showEditModal.value 
      ? `/admin/organizations/${authStore.userOrganization!.id}/events/${selectedEvent.value?.id}`
      : `/admin/organizations/${authStore.userOrganization!.id}/events`
    
    const method = showEditModal.value ? 'PUT' : 'POST'

    await $fetch(url, {
      method,
      baseURL: config.public.apiBase,
      headers: {
        Authorization: `Bearer ${authStore.token}`
      },
      body: eventForm.value
    })

    message.value = `Event ${showEditModal.value ? 'updated' : 'created'} successfully`
    messageType.value = 'success'
    closeModals()
    refresh()
    
    setTimeout(() => {
      message.value = ''
    }, 3000)
  } catch (error: any) {
    message.value = error.data?.message || `Failed to ${showEditModal.value ? 'update' : 'create'} event`
    messageType.value = 'error'
    
    setTimeout(() => {
      message.value = ''
    }, 3000)
  } finally {
    eventLoading.value = false
  }
}

const closeModals = () => {
  showCreateModal.value = false
  showEditModal.value = false
  selectedEvent.value = null
  eventForm.value = {
    title: '',
    description: '',
    venue: '',
    date: '',
    price: 0,
    max_attendees: 0
  }
}

// SEO
useHead({
  title: 'Admin Dashboard - Event Management',
  meta: [
    { name: 'description', content: 'Admin dashboard for managing events and attendees' }
  ]
})
</script> 