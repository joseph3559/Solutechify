<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
      <div class="p-6">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-xl font-semibold text-gray-900">Register for Event</h2>
          <button
            @click="$emit('close')"
            class="text-gray-400 hover:text-gray-600"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <div class="mb-6">
          <h3 class="text-lg font-medium text-gray-900">{{ event?.title }}</h3>
          <p class="text-sm text-gray-600">{{ event?.venue }} • ${{ event?.price }}</p>
        </div>

        <form @submit.prevent="register" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
            <input
              v-model="form.name"
              type="text"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="Enter your full name"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
            <input
              v-model="form.email"
              type="email"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="Enter your email"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
            <input
              v-model="form.phone"
              type="tel"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="Enter your phone number"
            />
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
              class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="isLoading">Registering...</span>
              <span v-else>Register</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useAuthStore } from '~/stores/auth'
import { useNotification } from '~/composables/useNotification'

interface Event {
  id: number
  title: string
  venue: string
  price: number
  organization: {
    slug: string
  }
}

interface Props {
  event: Event
}

const props = defineProps<Props>()

const emit = defineEmits<{
  close: []
  registered: []
}>()

const config = useRuntimeConfig()
const authStore = useAuthStore()
const { success, error: showError } = useNotification()

const isLoading = ref(false)
const error = ref('')

const form = ref({
  name: authStore.currentUser?.name || '',
  email: authStore.currentUser?.email || '',
  phone: authStore.currentUser?.phone || ''
})

const register = async () => {
  error.value = ''
  isLoading.value = true

  try {
    const headers: Record<string, string> = {
      'Content-Type': 'application/json'
    }
    
    if (authStore.currentUser) {
      headers['Authorization'] = `Bearer ${useCookie('auth-token').value}`
    }

    await $fetch(`/organizations/${props.event.organization.slug}/events/${props.event.id}/register`, {
      method: 'POST',
      baseURL: config.public.apiBase,
      headers,
      body: form.value
    })

    success('Registration Successful', 'You have been registered for this event!')
    emit('registered')
  } catch (err: any) {
    error.value = err.data?.message || 'Failed to register for event'
  } finally {
    isLoading.value = false
  }
}
</script> 