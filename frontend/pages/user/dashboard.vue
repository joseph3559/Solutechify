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
            <div class="text-sm">
              <span class="text-gray-600">Welcome, </span>
              <span class="font-medium text-gray-900">{{ user?.name }}</span>
            </div>
            <NuxtLink
              to="/events"
              class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors"
            >
              Browse Events
            </NuxtLink>
            <button
              @click="showProfileModal = true"
              class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors"
            >
              Profile
            </button>
            <button
              @click="handleLogout"
              class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors"
            >
              Logout
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Welcome Section -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Dashboard</h1>
        <p class="mt-2 text-gray-600">Manage your event registrations and profile</p>
      </div>

      <!-- Stats Grid -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6">
          <div class="flex items-center">
            <div class="p-2 bg-blue-100 rounded-lg">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Total Events</p>
              <p class="text-2xl font-bold text-gray-900">{{ (dashboardData as any)?.stats?.total_events || 0 }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
          <div class="flex items-center">
            <div class="p-2 bg-green-100 rounded-lg">
              <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Upcoming Events</p>
              <p class="text-2xl font-bold text-gray-900">{{ (dashboardData as any)?.stats?.upcoming_events || 0 }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
          <div class="flex items-center">
            <div class="p-2 bg-purple-100 rounded-lg">
              <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Past Events</p>
              <p class="text-2xl font-bold text-gray-900">{{ (dashboardData as any)?.stats?.past_events || 0 }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
          <div class="flex items-center">
            <div class="p-2 bg-red-100 rounded-lg">
              <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Cancelled</p>
              <p class="text-2xl font-bold text-gray-900">{{ (dashboardData as any)?.stats?.cancelled_events || 0 }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Events Sections -->
      <div class="space-y-8">
        <!-- Upcoming Events -->
        <div class="bg-white rounded-xl shadow-sm">
          <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Upcoming Events</h2>
          </div>
          <div class="p-6">
            <div v-if="(dashboardData as any)?.events?.upcoming?.length" class="space-y-4">
              <div
                v-for="registration in (dashboardData as any).events.upcoming"
                :key="registration.id"
                class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow"
              >
                <div class="flex items-start justify-between">
                  <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900">{{ registration.event.title }}</h3>
                    <p class="text-sm text-gray-600">{{ registration.event.organization?.name }}</p>
                    <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
                      <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        {{ formatDate(registration.event.date) }}
                      </div>
                      <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ registration.event.venue }}
                      </div>
                    </div>
                  </div>
                  <div class="flex space-x-2">
                    <NuxtLink
                      :to="`/events/${registration.event.id}`"
                      class="px-3 py-1 text-sm bg-blue-100 text-blue-800 rounded-lg hover:bg-blue-200 transition-colors"
                    >
                      View Details
                    </NuxtLink>
                    <button
                      @click="cancelRegistration(registration.id)"
                      class="px-3 py-1 text-sm bg-red-100 text-red-800 rounded-lg hover:bg-red-200 transition-colors"
                    >
                      Cancel
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <div v-else class="text-center py-8">
              <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
              <p class="text-gray-600">No upcoming events. <NuxtLink to="/events" class="text-blue-600 hover:text-blue-800">Browse events</NuxtLink> to register.</p>
            </div>
          </div>
        </div>

        <!-- Past Events -->
        <div class="bg-white rounded-xl shadow-sm">
          <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Past Events</h2>
          </div>
          <div class="p-6">
            <div v-if="(dashboardData as any)?.events?.past?.length" class="space-y-4">
              <div
                v-for="registration in (dashboardData as any).events.past"
                :key="registration.id"
                class="border border-gray-200 rounded-lg p-4"
              >
                <div class="flex items-start justify-between">
                  <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900">{{ registration.event.title }}</h3>
                    <p class="text-sm text-gray-600">{{ registration.event.organization?.name }}</p>
                    <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
                      <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        {{ formatDate(registration.event.date) }}
                      </div>
                      <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ registration.event.venue }}
                      </div>
                    </div>
                  </div>
                  <span class="px-3 py-1 text-sm bg-gray-100 text-gray-800 rounded-lg">Completed</span>
                </div>
              </div>
            </div>
            <div v-else class="text-center py-8">
              <p class="text-gray-600">No past events yet.</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Profile Modal -->
    <ProfileModal 
      v-if="showProfileModal && user"
      :user="user"
      @close="showProfileModal = false"
      @updated="refreshDashboard"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useAuthStore } from '~/stores/auth'
import { useNotification } from '~/composables/useNotification'

definePageMeta({
  middleware: 'auth'
})

useHead({
  title: 'User Dashboard - Solutechify Events'
})

const config = useRuntimeConfig()
const authStore = useAuthStore()
const { success, error } = useNotification()

const user = computed(() => authStore.currentUser)
const showProfileModal = ref(false)

// Fetch user dashboard data
const { data: dashboardData, refresh: refreshDashboard } = await useFetch('/user/dashboard', {
  baseURL: config.public.apiBase,
  headers: {
    'Authorization': `Bearer ${useCookie('auth-token').value}`
  },
  server: false
})

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const cancelRegistration = async (registrationId: number) => {
  if (!confirm('Are you sure you want to cancel this registration?')) return

  try {
    await $fetch(`${config.public.apiBase}/user/registrations/${registrationId}`, {
      method: 'DELETE',
      headers: {
        'Authorization': `Bearer ${useCookie('auth-token').value}`
      }
    })
    
    success('Registration Cancelled', 'Your registration has been cancelled successfully.')
    await refreshDashboard()
  } catch (err: any) {
    error('Cancel Failed', err.data?.message || 'Failed to cancel registration.')
  }
}

const handleLogout = async () => {
  await authStore.logout()
}
</script> 