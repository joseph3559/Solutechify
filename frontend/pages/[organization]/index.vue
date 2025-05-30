<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-6">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ organization?.name }}</h1>
            <p class="text-gray-600 mt-1">{{ organization?.description || 'Welcome to our events' }}</p>
          </div>
          <div class="flex space-x-4">
            <NuxtLink to="/admin/login" class="text-blue-600 hover:text-blue-800">
              Admin Login
            </NuxtLink>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Loading State -->
      <div v-if="pending" class="flex justify-center items-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="text-center py-12">
        <div class="text-red-600 text-lg">{{ error.message || 'Failed to load events' }}</div>
      </div>

      <!-- Events List -->
      <div v-else>
        <div class="mb-8">
          <h2 class="text-2xl font-bold text-gray-900 mb-4">Upcoming Events</h2>
          <p class="text-gray-600">Discover and register for our upcoming events</p>
        </div>

        <!-- No Events -->
        <div v-if="!events || events.length === 0" class="text-center py-12">
          <div class="text-gray-500 text-lg">No upcoming events at the moment.</div>
          <p class="text-gray-400 mt-2">Check back soon for new events!</p>
        </div>

        <!-- Events Grid -->
        <div v-else class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
          <div
            v-for="event in events"
            :key="event.id"
            class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow"
          >
            <div class="p-6">
              <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ event.title }}</h3>
              <p class="text-gray-600 mb-4 line-clamp-3">{{ event.description }}</p>
              
              <div class="space-y-2 mb-4">
                <div class="flex items-center text-sm text-gray-500">
                  <CalendarIcon class="h-4 w-4 mr-2" />
                  {{ formatDate(event.date) }}
                </div>
                <div class="flex items-center text-sm text-gray-500">
                  <MapPinIcon class="h-4 w-4 mr-2" />
                  {{ event.venue }}
                </div>
                <div class="flex items-center text-sm text-gray-500">
                  <CurrencyDollarIcon class="h-4 w-4 mr-2" />
                  ${{ event.price }}
                </div>
                <div class="flex items-center text-sm text-gray-500">
                  <UsersIcon class="h-4 w-4 mr-2" />
                  {{ event.current_attendees || 0 }} / {{ event.max_attendees }} attendees
                </div>
              </div>

              <button
                @click="openRegistrationModal(event)"
                :disabled="(event.current_attendees || 0) >= event.max_attendees"
                class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors"
              >
                {{ (event.current_attendees || 0) >= event.max_attendees ? 'Event Full' : 'Register Now' }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Registration Modal -->
    <div v-if="showRegistrationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-lg max-w-md w-full p-6">
        <h3 class="text-lg font-semibold mb-4">Register for {{ selectedEvent?.title }}</h3>
        
        <form @submit.prevent="submitRegistration">
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
              <input
                v-model="registrationForm.name"
                type="text"
                required
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
              <input
                v-model="registrationForm.email"
                type="email"
                required
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
              <input
                v-model="registrationForm.phone"
                type="tel"
                required
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
          </div>

          <div class="flex space-x-3 mt-6">
            <button
              type="button"
              @click="closeRegistrationModal"
              class="flex-1 bg-gray-300 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-400 transition-colors"
            >
              Cancel
            </button>
            <button
              type="submit"
              :disabled="registrationLoading"
              class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 disabled:bg-blue-400 transition-colors"
            >
              {{ registrationLoading ? 'Registering...' : 'Register' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Success/Error Messages -->
    <div v-if="message" class="fixed bottom-4 right-4 p-4 rounded-md shadow-lg z-50" :class="messageType === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'">
      {{ message }}
    </div>
  </div>
</template>

<script setup lang="ts">
import { CalendarIcon, MapPinIcon, CurrencyDollarIcon, UsersIcon } from '@heroicons/vue/24/outline'
import type { Organization, Event, EventRegistrationData } from '@/types'

const route = useRoute()
const config = useRuntimeConfig()

const organizationSlug = route.params.organization as string

// Reactive data
const showRegistrationModal = ref(false)
const selectedEvent = ref<Event | null>(null)
const registrationLoading = ref(false)
const message = ref('')
const messageType = ref<'success' | 'error'>('success')

const registrationForm = ref<EventRegistrationData>({
  name: '',
  email: '',
  phone: ''
})

// Fetch organization and events
const { data: organization, error: orgError } = await useFetch<Organization>(`/organizations/${organizationSlug}`, {
  baseURL: config.public.apiBase,
  server: false
})

const { data: events, pending, error, refresh } = await useFetch<Event[]>(`/organizations/${organizationSlug}/events`, {
  baseURL: config.public.apiBase,
  server: false
})

// Methods
const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const openRegistrationModal = (event: Event) => {
  selectedEvent.value = event
  showRegistrationModal.value = true
}

const closeRegistrationModal = () => {
  showRegistrationModal.value = false
  selectedEvent.value = null
  registrationForm.value = { name: '', email: '', phone: '' }
}

const submitRegistration = async () => {
  if (!selectedEvent.value) return

  registrationLoading.value = true
  
  try {
    const response = await $fetch(`/organizations/${organizationSlug}/events/${selectedEvent.value.id}/register`, {
      method: 'POST',
      baseURL: config.public.apiBase,
      body: registrationForm.value
    })

    message.value = 'Registration successful! You will receive a confirmation email shortly.'
    messageType.value = 'success'
    closeRegistrationModal()
    refresh() // Refresh events to update attendee count
    
    setTimeout(() => {
      message.value = ''
    }, 5000)
  } catch (error: any) {
    message.value = error.data?.message || 'Registration failed. Please try again.'
    messageType.value = 'error'
    
    setTimeout(() => {
      message.value = ''
    }, 5000)
  } finally {
    registrationLoading.value = false
  }
}

// Show error message if organization not found
if (orgError.value) {
  throw createError({
    statusCode: 404,
    statusMessage: 'Organization not found'
  })
}

// SEO
useHead({
  title: `${organization.value?.name || 'Events'} - Event Management`,
  meta: [
    { name: 'description', content: `Discover and register for events by ${organization.value?.name}` }
  ]
})
</script>

<style scoped>
.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style> 