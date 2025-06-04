<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-screen overflow-y-auto">
      <div class="p-6">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-xl font-semibold text-gray-900">Create New Event</h2>
          <button
            @click="$emit('close')"
            class="text-gray-400 hover:text-gray-600"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <form @submit.prevent="createEvent" class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-1">Event Title *</label>
              <input
                v-model="form.title"
                type="text"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Enter event title"
              />
            </div>

            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
              <textarea
                v-model="form.description"
                rows="4"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Describe your event..."
              ></textarea>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Venue *</label>
              <input
                v-model="form.venue"
                type="text"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Event venue"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Date & Time *</label>
              <input
                v-model="form.date"
                type="datetime-local"
                required
                :min="minDateTime"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Price ($) *</label>
              <input
                v-model="form.price"
                type="number"
                step="0.01"
                min="0"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="0.00"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Max Attendees *</label>
              <input
                v-model="form.max_attendees"
                type="number"
                min="1"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="50"
              />
            </div>

            <div v-if="user?.role === 'super_admin'" class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-1">Organization *</label>
              <select
                v-model="form.organization_id"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              >
                <option value="">Select Organization</option>
                <option v-for="org in organizations" :key="org.id" :value="org.id">
                  {{ org.name }}
                </option>
              </select>
            </div>
          </div>

          <div v-if="error" class="bg-red-50 border border-red-200 rounded-lg p-3">
            <p class="text-sm text-red-600">{{ error }}</p>
          </div>

          <div class="flex justify-end space-x-3 pt-4">
            <button
              type="button"
              @click="$emit('close')"
              class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors"
            >
              Cancel
            </button>
            <button
              type="submit"
              :disabled="isLoading"
              class="px-6 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="isLoading">Creating...</span>
              <span v-else>Create Event</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '~/stores/auth'
import { useNotification } from '~/composables/useNotification'

const emit = defineEmits<{
  close: []
  created: []
}>()

const config = useRuntimeConfig()
const authStore = useAuthStore()
const { success, error: showError } = useNotification()

const user = computed(() => authStore.currentUser)
const isLoading = ref(false)
const error = ref('')
const organizations = ref<any[]>([])

const form = ref({
  title: '',
  description: '',
  venue: '',
  date: '',
  price: 0,
  max_attendees: 50,
  organization_id: '' as string | number
})

const minDateTime = computed(() => {
  const now = new Date()
  now.setMinutes(now.getMinutes() - now.getTimezoneOffset())
  return now.toISOString().slice(0, 16)
})

onMounted(async () => {
  // Set default organization for non-super admins
  if (user.value?.role !== 'super_admin' && user.value?.organization_id) {
    form.value.organization_id = user.value.organization_id
  }

  // Load organizations for super admins and set default
  if (user.value?.role === 'super_admin') {
    try {
      const data = await $fetch('/admin/organizations', {
        baseURL: config.public.apiBase,
        headers: {
          'Authorization': `Bearer ${useCookie('auth-token').value}`
        }
      })
      organizations.value = data as any[]
      
      // Set default to user's organization if they have one
      if (user.value?.organization_id && organizations.value.length > 0) {
        form.value.organization_id = user.value.organization_id
      }
    } catch (err) {
      console.error('Failed to load organizations:', err)
    }
  }
})

const createEvent = async () => {
  error.value = ''
  isLoading.value = true

  try {
    let eventData = { ...form.value }
    
    // Ensure we have a valid organization_id
    if (!eventData.organization_id) {
      if (user.value?.role === 'super_admin') {
        error.value = 'Please select an organization'
        return
      } else if (user.value?.organization_id) {
        eventData.organization_id = user.value.organization_id
      } else {
        error.value = 'No organization found for user'
        return
      }
    }

    // Convert string to number if needed
    if (typeof eventData.organization_id === 'string') {
      eventData.organization_id = parseInt(eventData.organization_id)
    }

    await $fetch(`/admin/organizations/${eventData.organization_id}/events`, {
      method: 'POST',
      baseURL: config.public.apiBase,
      headers: {
        'Authorization': `Bearer ${useCookie('auth-token').value}`,
        'Content-Type': 'application/json'
      },
      body: eventData
    })

    success('Event Created', 'Your event has been created successfully!')
    emit('created')
    emit('close')
  } catch (err: any) {
    error.value = err.data?.message || 'Failed to create event'
  } finally {
    isLoading.value = false
  }
}
</script> 