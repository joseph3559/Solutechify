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
              <button
                @click="handleLogout"
                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors"
              >
                Logout
              </button>
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

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Hero Section -->
      <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Discover Amazing Events</h1>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">
          Find and register for events from top organizations. Connect, learn, and grow with like-minded people.
        </p>
      </div>

      <!-- Filters -->
      <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Search Events</label>
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Search by title or venue..."
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Organization</label>
            <select
              v-model="selectedOrganization"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="">All Organizations</option>
              <option v-for="org in (organizations as any[])" :key="org.id" :value="org.id">
                {{ org.name }}
              </option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Date Filter</label>
            <select
              v-model="dateFilter"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="all">All Dates</option>
              <option value="upcoming">Upcoming Events</option>
              <option value="this_week">This Week</option>
              <option value="this_month">This Month</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Price Range</label>
            <select
              v-model="priceFilter"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="all">All Prices</option>
              <option value="free">Free Events</option>
              <option value="0-50">$0 - $50</option>
              <option value="50-100">$50 - $100</option>
              <option value="100+">$100+</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Events Grid -->
      <div v-if="filteredEvents?.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="event in filteredEvents"
          :key="event.id"
          class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden"
        >
          <div class="p-6">
            <div class="flex items-start justify-between mb-3">
              <h3 class="text-lg font-semibold text-gray-900 line-clamp-2">{{ event.title }}</h3>
              <span class="flex-shrink-0 ml-2 px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                ${{ event.price }}
              </span>
            </div>
            
            <p class="text-sm text-gray-600 mb-2">{{ event.organization?.name }}</p>
            
            <div class="space-y-2 mb-4">
              <div class="flex items-center text-sm text-gray-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                {{ formatDate(event.date) }}
              </div>
              <div class="flex items-center text-sm text-gray-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                {{ event.venue }}
              </div>
              <div class="flex items-center justify-between text-sm text-gray-500">
                <div class="flex items-center">
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                  </svg>
                  {{ event.current_attendees || 0 }} / {{ event.max_attendees }}
                </div>
                <span :class="event.current_attendees >= event.max_attendees ? 'text-red-600' : 'text-green-600'">
                  {{ event.current_attendees >= event.max_attendees ? 'Full' : 'Available' }}
                </span>
              </div>
            </div>
            
            <p v-if="event.description" class="text-sm text-gray-600 mb-4 line-clamp-3">
              {{ event.description }}
            </p>
            
            <div class="flex space-x-2">
              <NuxtLink
                :to="`/events/${event.id}`"
                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg text-sm font-medium text-center transition-colors"
              >
                View Details
              </NuxtLink>
              <button
                v-if="event.current_attendees < event.max_attendees && isUpcoming(event.date)"
                @click="quickRegister(event)"
                class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg text-sm font-medium transition-colors"
              >
                Register
              </button>
              <span
                v-else
                class="flex-1 bg-gray-300 text-gray-500 py-2 px-4 rounded-lg text-sm font-medium text-center cursor-not-allowed"
              >
                {{ event.current_attendees >= event.max_attendees ? 'Full' : 'Past Event' }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- No Events -->
      <div v-else class="text-center py-12">
        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">No Events Found</h3>
        <p class="text-gray-600">Try adjusting your search filters to find more events.</p>
      </div>
    </div>

    <!-- Registration Modal -->
    <PublicEventRegistrationModal
      v-if="showRegistrationModal && selectedEvent"
      :event="selectedEvent"
      @close="showRegistrationModal = false"
      @registered="handleRegistrationSuccess"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { useAuthStore } from '~/stores/auth'
import { useNotification } from '~/composables/useNotification'

useHead({
  title: 'Events - Solutechify Events'
})

const config = useRuntimeConfig()
const authStore = useAuthStore()
const { success, error } = useNotification()

const user = computed(() => authStore.currentUser)
const showRegistrationModal = ref(false)
const selectedEvent = ref(null)

// Filter states
const searchQuery = ref('')
const selectedOrganization = ref('')
const dateFilter = ref('all')
const priceFilter = ref('all')

// Fetch data
const { data: allEvents } = await useFetch('/events', {
  baseURL: config.public.apiBase,
  server: false
})

const { data: organizations } = await useFetch('/organizations', {
  baseURL: config.public.apiBase,
  server: false
})

// Computed filtered events
const filteredEvents = computed(() => {
  if (!allEvents.value || !Array.isArray(allEvents.value)) return []
  
  let filtered = [...(allEvents.value as any[])]
  
  // Search filter
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter((event: any) => 
      event.title.toLowerCase().includes(query) ||
      event.venue.toLowerCase().includes(query) ||
      event.description?.toLowerCase().includes(query)
    )
  }
  
  // Organization filter
  if (selectedOrganization.value) {
    filtered = filtered.filter((event: any) => 
      event.organization_id === parseInt(selectedOrganization.value)
    )
  }
  
  // Date filter
  if (dateFilter.value !== 'all') {
    const now = new Date()
    filtered = filtered.filter((event: any) => {
      const eventDate = new Date(event.date)
      
      switch (dateFilter.value) {
        case 'upcoming':
          return eventDate > now
        case 'this_week':
          const weekFromNow = new Date(now.getTime() + 7 * 24 * 60 * 60 * 1000)
          return eventDate > now && eventDate <= weekFromNow
        case 'this_month':
          const monthFromNow = new Date(now.getFullYear(), now.getMonth() + 1, now.getDate())
          return eventDate > now && eventDate <= monthFromNow
        default:
          return true
      }
    })
  }
  
  // Price filter
  if (priceFilter.value !== 'all') {
    filtered = filtered.filter((event: any) => {
      const price = parseFloat(event.price)
      
      switch (priceFilter.value) {
        case 'free':
          return price === 0
        case '0-50':
          return price >= 0 && price <= 50
        case '50-100':
          return price > 50 && price <= 100
        case '100+':
          return price > 100
        default:
          return true
      }
    })
  }
  
  return filtered
})

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

const isUpcoming = (dateString: string) => {
  return new Date(dateString) > new Date()
}

const quickRegister = (event: any) => {
  if (!user.value) {
    error('Login Required', 'Please login to register for events.')
    navigateTo('/auth')
    return
  }
  
  selectedEvent.value = event
  showRegistrationModal.value = true
}

const handleRegistrationSuccess = () => {
  showRegistrationModal.value = false
  selectedEvent.value = null
  // Refresh events to update attendee counts
  refreshCookie('allEvents')
}

const handleLogout = async () => {
  await authStore.logout()
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style> 