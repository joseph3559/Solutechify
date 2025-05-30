<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between py-6">
          <div class="flex items-center space-x-4">
            <NuxtLink :to="`/${organization?.slug}`" class="text-blue-600 hover:text-blue-800 flex items-center">
              <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
              </svg>
              Back to Events
            </NuxtLink>
            <div class="text-gray-300">|</div>
            <h1 class="text-2xl font-bold text-gray-900">{{ organization?.name }}</h1>
          </div>
          <NuxtLink to="/admin/login" class="text-blue-600 hover:text-blue-800">
            Admin Login
          </NuxtLink>
        </div>
      </div>
    </header>

    <!-- Loading State -->
    <div v-if="pending" class="flex justify-center items-center py-20">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="text-center">
        <div class="text-red-600 text-lg mb-4">{{ error.message || 'Failed to load event' }}</div>
        <NuxtLink :to="`/${route.params.organization}`" class="text-blue-600 hover:text-blue-800">
          ← Return to Events
        </NuxtLink>
      </div>
    </div>

    <!-- Event Content -->
    <div v-else-if="event" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Event Information -->
        <div class="lg:col-span-2">
          <!-- Event Hero -->
          <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-8 text-white">
              <div class="flex items-center justify-between mb-4">
                <span class="bg-white/20 text-white px-3 py-1 rounded-full text-sm font-medium">
                  {{ formatEventDate(event.date) }}
                </span>
                <span v-if="event.price > 0" class="text-2xl font-bold">
                  ${{ event.price }}
                </span>
                <span v-else class="text-xl font-semibold text-green-200">
                  FREE
                </span>
              </div>
              <h1 class="text-4xl font-bold mb-4">{{ event.title }}</h1>
              <div class="flex items-center space-x-6 text-lg">
                <div class="flex items-center">
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                  </svg>
                  {{ event.venue }}
                </div>
                <div class="flex items-center">
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                  </svg>
                  {{ event.current_attendees }} / {{ event.max_attendees }} attendees
                </div>
              </div>
            </div>
          </div>

          <!-- Event Description -->
          <div class="bg-white rounded-xl shadow-sm p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">About This Event</h2>
            <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
              {{ event.description }}
            </div>
          </div>

          <!-- Event Details Grid -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Date & Time
              </h3>
              <p class="text-gray-700">{{ formatEventDateTime(event.date) }}</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Location
              </h3>
              <p class="text-gray-700">{{ event.venue }}</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
                Price
              </h3>
              <p class="text-gray-700">
                <span v-if="event.price > 0" class="text-2xl font-bold text-green-600">${{ event.price }}</span>
                <span v-else class="text-2xl font-bold text-green-600">FREE</span>
              </p>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Capacity
              </h3>
              <p class="text-gray-700">
                {{ event.current_attendees }} of {{ event.max_attendees }} spots filled
              </p>
              <div class="mt-2 bg-gray-200 rounded-full h-2">
                <div 
                  class="bg-blue-600 h-2 rounded-full" 
                  :style="`width: ${(event.current_attendees / event.max_attendees) * 100}%`"
                ></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Registration Sidebar -->
        <div class="lg:col-span-1">
          <div class="sticky top-8">
            <!-- Registration Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
              <h3 class="text-xl font-semibold text-gray-900 mb-6">Register for Event</h3>
              
              <!-- Registration Status -->
              <div v-if="event.current_attendees >= event.max_attendees" class="text-center p-6 bg-red-50 rounded-lg">
                <svg class="w-12 h-12 text-red-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h4 class="text-lg font-semibold text-red-800 mb-2">Event Full</h4>
                <p class="text-red-600">This event has reached maximum capacity.</p>
              </div>

              <!-- Registration Form -->
              <form v-else @submit.prevent="submitRegistration" class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                  <input 
                    v-model="registrationForm.name"
                    type="text" 
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter your full name"
                  >
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                  <input 
                    v-model="registrationForm.email"
                    type="email" 
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter your email"
                  >
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                  <input 
                    v-model="registrationForm.phone"
                    type="tel" 
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter your phone number"
                  >
                </div>

                <!-- Registration Button -->
                <button
                  type="submit"
                  :disabled="isSubmitting"
                  class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  <span v-if="isSubmitting" class="flex items-center justify-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Processing...
                  </span>
                  <span v-else>Register Now</span>
                </button>

                <!-- Price Info -->
                <div class="text-center pt-4 border-t">
                  <p class="text-sm text-gray-600">
                    <span v-if="event.price > 0">
                      Registration fee: <span class="font-semibold text-green-600">${{ event.price }}</span>
                    </span>
                    <span v-else class="font-semibold text-green-600">Free Registration</span>
                  </p>
                </div>
              </form>
            </div>

            <!-- Event Organizer Info -->
            <div class="bg-white rounded-xl shadow-sm p-6">
              <h4 class="text-lg font-semibold text-gray-900 mb-4">Event Organizer</h4>
              <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                  <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H9m0 0H5m0 0H3m2 0v-8a2 2 0 012-2h4a2 2 0 012 2v8"></path>
                  </svg>
                </div>
                <div>
                  <h5 class="font-semibold text-gray-900">{{ organization?.name }}</h5>
                  <p class="text-sm text-gray-600">{{ organization?.email }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Success Modal -->
    <div v-if="showSuccessModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-xl max-w-md w-full p-6">
        <div class="text-center">
          <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
          </div>
          <h3 class="text-xl font-semibold text-gray-900 mb-2">Registration Successful!</h3>
          <p class="text-gray-600 mb-6">You have successfully registered for {{ event?.title }}. A confirmation email will be sent to you shortly.</p>
          <button 
            @click="showSuccessModal = false"
            class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg font-semibold hover:bg-blue-700 transition-colors"
          >
            Close
          </button>
        </div>
      </div>
    </div>

    <!-- Error Message -->
    <div v-if="registrationError" class="fixed bottom-4 right-4 bg-red-600 text-white p-4 rounded-lg shadow-lg z-50 max-w-sm">
      <div class="flex items-center justify-between">
        <p class="text-sm">{{ registrationError }}</p>
        <button @click="registrationError = ''" class="ml-4 text-white hover:text-gray-200">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Event, Organization } from '@/types'

const route = useRoute()
const config = useRuntimeConfig()

// Reactive data
const showSuccessModal = ref(false)
const isSubmitting = ref(false)
const registrationError = ref('')

const registrationForm = ref({
  name: '',
  email: '',
  phone: ''
})

// Fetch event data
const eventUrl = `/organizations/${route.params.organization}/events/${route.params.id}`
const event = ref<Event | null>(null)
const pending = ref(true)
const error = ref<any>(null)

try {
  const data = await $fetch<Event>(eventUrl, {
    baseURL: config.public.apiBase
  })
  event.value = data
} catch (err: any) {
  error.value = err
} finally {
  pending.value = false
}

// Fetch organization data
const organization = ref<Organization | null>(null)

try {
  const orgData = await $fetch<Organization>(`/organizations/${route.params.organization}`, {
    baseURL: config.public.apiBase
  })
  organization.value = orgData
} catch (err) {
  console.error('Organization fetch error:', err)
}

// Methods
function formatEventDate(dateString: string) {
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', { 
    weekday: 'long', 
    year: 'numeric', 
    month: 'long', 
    day: 'numeric' 
  })
}

function formatEventDateTime(dateString: string) {
  const date = new Date(dateString)
  return date.toLocaleString('en-US', { 
    weekday: 'long', 
    year: 'numeric', 
    month: 'long', 
    day: 'numeric',
    hour: 'numeric',
    minute: '2-digit',
    hour12: true
  })
}

async function submitRegistration() {
  if (isSubmitting.value) return
  
  isSubmitting.value = true
  registrationError.value = ''

  try {
    await $fetch(`/organizations/${route.params.organization}/events/${route.params.id}/register`, {
      method: 'POST',
      baseURL: config.public.apiBase,
      body: registrationForm.value
    })

    // Update attendee count
    if (event.value) {
      event.value.current_attendees += 1
    }

    // Reset form and show success
    registrationForm.value = { name: '', email: '', phone: '' }
    showSuccessModal.value = true

  } catch (err: any) {
    registrationError.value = err.data?.message || 'Failed to register for event. Please try again.'
  } finally {
    isSubmitting.value = false
  }
}

// SEO
useHead({
  title: () => event.value ? `${event.value.title} - ${organization.value?.name}` : 'Event Details',
  meta: [
    { name: 'description', content: () => event.value?.description || 'Event details and registration' }
  ]
})
</script> 