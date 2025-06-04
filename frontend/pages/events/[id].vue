<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <NuxtLink to="/" class="flex items-center">
              <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                <span class="text-white font-bold text-sm">SE</span>
              </div>
              <span class="ml-2 text-xl font-bold text-gray-900">Solutechify Events</span>
            </NuxtLink>
          </div>
          
          <div class="flex items-center space-x-4">
            <NuxtLink
              to="/events"
              class="text-blue-600 hover:text-blue-800 text-sm font-medium"
            >
              ← Back to Events
            </NuxtLink>
            <div v-if="user" class="text-sm">
              <span class="text-gray-600">Welcome, </span>
              <span class="font-medium text-gray-900">{{ user.name }}</span>
            </div>
            <div v-if="user" class="flex space-x-2">
              <NuxtLink
                :to="user.role === 'admin' || user.role === 'super_admin' || user.role === 'organizer' ? '/admin/dashboard' : '/user/dashboard'"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors"
              >
                Dashboard
              </NuxtLink>
            </div>
            <div v-else>
              <NuxtLink
                to="/auth"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors"
              >
                Login / Register
              </NuxtLink>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="pending" class="flex justify-center items-center min-h-96">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="text-center">
        <h1 class="text-2xl font-bold text-gray-900">Event Not Found</h1>
        <p class="text-gray-600 mt-2">The event you're looking for doesn't exist or has been removed.</p>
        <NuxtLink
          to="/events"
          class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors"
        >
          View All Events
        </NuxtLink>
      </div>
    </div>

    <!-- Event Details -->
    <div v-else-if="event" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <!-- Header -->
        <div class="p-8 border-b border-gray-200">
          <div class="flex items-start justify-between">
            <div class="flex-1">
              <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ (event as any)?.title }}</h1>
              <p class="text-lg text-gray-600 mb-4">{{ (event as any)?.organization?.name }}</p>
              
              <div class="flex flex-wrap gap-4 text-sm text-gray-500">
                <div class="flex items-center">
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                  </svg>
                  {{ formatDate((event as any)?.date) }}
                </div>
                <div class="flex items-center">
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                  </svg>
                  {{ (event as any)?.venue }}
                </div>
                <div class="flex items-center">
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                  </svg>
                  ${{ (event as any)?.price }}
                </div>
                <div class="flex items-center">
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                  </svg>
                  {{ (event as any)?.current_attendees || 0 }} / {{ (event as any)?.max_attendees }} attendees
                </div>
              </div>
            </div>
            
            <div class="ml-8 flex-shrink-0">
              <div class="text-right mb-4">
                <span :class="getSpotsAvailableClass()" class="inline-flex px-3 py-1 text-sm font-medium rounded-full">
                  {{ getSpotsAvailableText() }}
                </span>
              </div>
              
              <button
                v-if="canRegister"
                @click="showRegistrationModal = true"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors"
              >
                Register Now
              </button>
              <span
                v-else
                class="bg-gray-300 text-gray-500 px-6 py-3 rounded-lg font-medium cursor-not-allowed"
              >
                {{ getRegisterButtonText() }}
              </span>
            </div>
          </div>
        </div>
        
        <!-- Description -->
        <div class="p-8">
          <h2 class="text-xl font-semibold text-gray-900 mb-4">About This Event</h2>
          <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ (event as any)?.description }}</p>
        </div>
        
        <!-- Additional Info -->
        <div class="p-8 bg-gray-50 border-t border-gray-200">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <h3 class="text-lg font-medium text-gray-900 mb-3">Event Details</h3>
              <dl class="space-y-2">
                <div class="flex justify-between">
                  <dt class="text-sm text-gray-600">Date & Time:</dt>
                  <dd class="text-sm font-medium text-gray-900">{{ formatDetailedDate((event as any)?.date) }}</dd>
                </div>
                <div class="flex justify-between">
                  <dt class="text-sm text-gray-600">Duration:</dt>
                  <dd class="text-sm font-medium text-gray-900">TBD</dd>
                </div>
                <div class="flex justify-between">
                  <dt class="text-sm text-gray-600">Language:</dt>
                  <dd class="text-sm font-medium text-gray-900">English</dd>
                </div>
              </dl>
            </div>
            
            <div>
              <h3 class="text-lg font-medium text-gray-900 mb-3">Registration Info</h3>
              <dl class="space-y-2">
                <div class="flex justify-between">
                  <dt class="text-sm text-gray-600">Price:</dt>
                  <dd class="text-sm font-medium text-gray-900">${{ (event as any)?.price }}</dd>
                </div>
                <div class="flex justify-between">
                  <dt class="text-sm text-gray-600">Spots Available:</dt>
                  <dd class="text-sm font-medium text-gray-900">{{ Math.max(0, ((event as any)?.max_attendees || 0) - ((event as any)?.current_attendees || 0)) }}</dd>
                </div>
                <div class="flex justify-between">
                  <dt class="text-sm text-gray-600">Registration Status:</dt>
                  <dd class="text-sm font-medium text-gray-900">{{ getRegistrationStatus() }}</dd>
                </div>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Registration Modal -->
    <PublicEventRegistrationModal
      v-if="showRegistrationModal && event"
      :event="event as any"
      @close="showRegistrationModal = false"
      @registered="handleRegistrationSuccess"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useAuthStore } from '~/stores/auth'
import { useNotification } from '~/composables/useNotification'

const route = useRoute()
const config = useRuntimeConfig()
const authStore = useAuthStore()
const { success } = useNotification()

const user = computed(() => authStore.currentUser)
const showRegistrationModal = ref(false)

useHead({
  title: 'Event Details - Solutechify Events'
})

// Fetch event data
const { data: event, pending, error, refresh } = await useFetch(`/events/${route.params.id}`, {
  baseURL: config.public.apiBase,
  server: false
})

const canRegister = computed(() => {
  if (!event.value) return false
  const eventData = event.value as any
  const spotsAvailable = (eventData.max_attendees || 0) > (eventData.current_attendees || 0)
  const isUpcoming = new Date(eventData.date) > new Date()
  return spotsAvailable && isUpcoming
})

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

const formatDetailedDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
    timeZoneName: 'short'
  })
}

const getSpotsAvailableClass = () => {
  if (!event.value) return 'bg-gray-100 text-gray-800'
  const eventData = event.value as any
  const spotsLeft = (eventData.max_attendees || 0) - (eventData.current_attendees || 0)
  if (spotsLeft <= 0) return 'bg-red-100 text-red-800'
  if (spotsLeft <= 5) return 'bg-yellow-100 text-yellow-800'
  return 'bg-green-100 text-green-800'
}

const getSpotsAvailableText = () => {
  if (!event.value) return 'Unknown'
  const eventData = event.value as any
  const spotsLeft = (eventData.max_attendees || 0) - (eventData.current_attendees || 0)
  if (spotsLeft <= 0) return 'Sold Out'
  if (spotsLeft <= 5) return `Only ${spotsLeft} spots left`
  return `${spotsLeft} spots available`
}

const getRegisterButtonText = () => {
  if (!event.value) return 'Unavailable'
  const eventData = event.value as any
  const spotsLeft = (eventData.max_attendees || 0) - (eventData.current_attendees || 0)
  const isUpcoming = new Date(eventData.date) > new Date()
  
  if (!isUpcoming) return 'Event Ended'
  if (spotsLeft <= 0) return 'Sold Out'
  return 'Register Now'
}

const getRegistrationStatus = () => {
  if (!event.value) return 'Unknown'
  const eventData = event.value as any
  const isUpcoming = new Date(eventData.date) > new Date()
  if (!isUpcoming) return 'Closed'
  return 'Open'
}

const handleRegistrationSuccess = () => {
  showRegistrationModal.value = false
  refresh() // Refresh event data to update attendee count
}
</script> 