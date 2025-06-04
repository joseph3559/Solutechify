<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Welcome Section -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
      <p class="mt-2 text-gray-600">Manage events, organizations, and attendees</p>
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
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total Attendees</p>
            <p class="text-2xl font-bold text-gray-900">{{ (dashboardData as any)?.stats?.total_attendees || 0 }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center">
          <div class="p-2 bg-purple-100 rounded-lg">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H9m0 0H5m4 0v-2a1 1 0 011-1h1a1 1 0 011 1v2m-4 0V9a1 1 0 011-1h1a1 1 0 011 1v12"></path>
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Organizations</p>
            <p class="text-2xl font-bold text-gray-900">{{ (dashboardData as any)?.stats?.total_organizations || 0 }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center">
          <div class="p-2 bg-yellow-100 rounded-lg">
            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Upcoming Events</p>
            <p class="text-2xl font-bold text-gray-900">{{ (dashboardData as any)?.stats?.upcoming_events || 0 }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
      <button
        @click="showCreateEventModal = true"
        class="bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-lg flex items-center justify-center space-x-2 transition-colors"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        <span>Create Event</span>
      </button>

      <button
        @click="showCreateOrgModal = true"
        class="bg-green-600 hover:bg-green-700 text-white p-4 rounded-lg flex items-center justify-center space-x-2 transition-colors"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H9m0 0H5m4 0v-2a1 1 0 011-1h1a1 1 0 011 1v2m-4 0V9a1 1 0 011-1h1a1 1 0 011 1v12"></path>
        </svg>
        <span>Create Organization</span>
      </button>

      <NuxtLink
        to="/events"
        class="bg-purple-600 hover:bg-purple-700 text-white p-4 rounded-lg flex items-center justify-center space-x-2 transition-colors"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
        </svg>
        <span>View Public Events</span>
      </NuxtLink>

      <button
        @click="activeTab = 'attendees'"
        class="bg-orange-600 hover:bg-orange-700 text-white p-4 rounded-lg flex items-center justify-center space-x-2 transition-colors"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
        <span>View Attendees</span>
      </button>
    </div>

    <!-- Tab Navigation -->
    <div class="mb-6">
      <nav class="flex space-x-8">
        <button
          v-for="tab in tabs"
          :key="tab.id"
          @click="activeTab = tab.id"
          :class="[
            'py-2 px-1 border-b-2 font-medium text-sm transition-colors',
            activeTab === tab.id
              ? 'border-blue-500 text-blue-600'
              : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
          ]"
        >
          {{ tab.name }}
        </button>
      </nav>
    </div>

    <!-- Tab Content -->
    <div class="space-y-6">
      <!-- Events Tab -->
      <div v-if="activeTab === 'events'">
        <AdminEventList
          :events="(dashboardData as any)?.events || []"
          @edit="editEvent"
          @delete="deleteEvent"
          @refresh="refreshData"
        />
      </div>

      <!-- Organizations Tab -->
      <div v-if="activeTab === 'organizations'">
        <AdminOrganizationList
          :organizations="(dashboardData as any)?.organizations || []"
          @edit="editOrganization"
          @delete="deleteOrganization"
          @refresh="refreshData"
        />
      </div>

      <!-- Attendees Tab -->
      <div v-if="activeTab === 'attendees'">
        <AdminAttendeeList
          :attendees="(dashboardData as any)?.attendees || []"
          @refresh="refreshData"
        />
      </div>
    </div>
  </div>

  <!-- Modals -->
  <AdminCreateEventModal
    v-if="showCreateEventModal"
    @close="showCreateEventModal = false"
    @created="refreshData"
  />

  <AdminCreateOrgModal
    v-if="showCreateOrgModal"
    @close="showCreateOrgModal = false"
    @created="refreshData"
  />

  <AdminEditEventModal
    v-if="showEditEventModal && selectedEvent"
    :event="selectedEvent"
    @close="showEditEventModal = false"
    @updated="refreshData"
  />

  <AdminEditOrgModal
    v-if="showEditOrgModal && selectedOrganization"
    :organization="selectedOrganization"
    @close="showEditOrgModal = false"
    @updated="refreshData"
  />

  <ProfileModal
    v-if="showProfileModal && user"
    :user="user"
    @close="showProfileModal = false"
    @updated="refreshUserData"
  />
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useAuthStore } from '~/stores/auth'
import { useNotification } from '~/composables/useNotification'

definePageMeta({
  middleware: 'auth',
  layout: 'admin'
})

useHead({
  title: 'Admin Dashboard - Solutechify Events'
})

const config = useRuntimeConfig()
const authStore = useAuthStore()
const { success, error } = useNotification()

const user = computed(() => authStore.currentUser)
const showProfileModal = ref(false)
const showCreateEventModal = ref(false)
const showCreateOrgModal = ref(false)
const showEditEventModal = ref(false)
const showEditOrgModal = ref(false)
const activeTab = ref('events')
const selectedEvent = ref(null)
const selectedOrganization = ref(null)

const tabs = [
  { id: 'events', name: 'Events' },
  { id: 'organizations', name: 'Organizations' },
  { id: 'attendees', name: 'Attendees' }
]

// Fetch admin dashboard data
const { data: dashboardData, refresh: refreshDashboard } = await useFetch('/admin/dashboard', {
  baseURL: config.public.apiBase,
  headers: {
    'Authorization': `Bearer ${useCookie('auth-token').value}`
  },
  server: false
})

const refreshData = async () => {
  await refreshDashboard()
}

const refreshUserData = async () => {
  await authStore.getCurrentUser()
}

const editEvent = (event: any) => {
  selectedEvent.value = event
  showEditEventModal.value = true
}

const editOrganization = (organization: any) => {
  selectedOrganization.value = organization
  showEditOrgModal.value = true
}

const deleteEvent = async (eventId: number) => {
  if (!confirm('Are you sure you want to delete this event?')) return
  
  try {
    await $fetch(`${config.public.apiBase}/admin/events/${eventId}`, {
      method: 'DELETE',
      headers: {
        'Authorization': `Bearer ${useCookie('auth-token').value}`
      }
    })
    
    success('Event Deleted', 'Event has been deleted successfully.')
    await refreshData()
  } catch (err: any) {
    error('Delete Failed', err.data?.message || 'Failed to delete event.')
  }
}

const deleteOrganization = async (orgId: number) => {
  if (!confirm('Are you sure you want to delete this organization?')) return
  
  try {
    await $fetch(`${config.public.apiBase}/admin/organizations/${orgId}`, {
      method: 'DELETE',
      headers: {
        'Authorization': `Bearer ${useCookie('auth-token').value}`
      }
    })
    
    success('Organization Deleted', 'Organization has been deleted successfully.')
    await refreshData()
  } catch (err: any) {
    error('Delete Failed', err.data?.message || 'Failed to delete organization.')
  }
}

const handleLogout = async () => {
  await authStore.logout()
}
</script> 