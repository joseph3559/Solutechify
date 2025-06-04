<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
      <div class="p-6">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-xl font-semibold text-gray-900">Update Profile</h2>
          <button
            @click="$emit('close')"
            class="text-gray-400 hover:text-gray-600"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <form @submit.prevent="updateProfile" class="space-y-4">
          <!-- Name -->
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

          <!-- Email -->
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

          <!-- Phone -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
            <input
              v-model="form.phone"
              type="tel"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="Enter your phone number"
            />
          </div>

          <!-- Password Section -->
          <div class="border-t pt-4">
            <h3 class="text-sm font-medium text-gray-900 mb-3">Change Password (Optional)</h3>
            
            <div class="space-y-3">
              <!-- Current Password -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                <input
                  v-model="form.current_password"
                  type="password"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Enter current password"
                />
              </div>

              <!-- New Password -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                <input
                  v-model="form.password"
                  type="password"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Enter new password"
                />
              </div>

              <!-- Confirm New Password -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                <input
                  v-model="form.password_confirmation"
                  type="password"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Confirm new password"
                />
              </div>
            </div>
          </div>

          <!-- Error Display -->
          <div v-if="error" class="bg-red-50 border border-red-200 rounded-lg p-3">
            <p class="text-sm text-red-600">{{ error }}</p>
          </div>

          <!-- Action Buttons -->
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
              <span v-if="isLoading">Updating...</span>
              <span v-else>Update Profile</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useNotification } from '~/composables/useNotification'

interface User {
  id: number
  name: string
  email: string
  phone?: string
}

interface Props {
  user: User
}

const props = defineProps<Props>()

const emit = defineEmits<{
  close: []
  updated: []
}>()

const config = useRuntimeConfig()
const { success, error: showError } = useNotification()

const isLoading = ref(false)
const error = ref('')

const form = ref({
  name: '',
  email: '',
  phone: '',
  current_password: '',
  password: '',
  password_confirmation: ''
})

onMounted(() => {
  // Initialize form with user data
  form.value.name = props.user.name
  form.value.email = props.user.email
  form.value.phone = props.user.phone || ''
})

const updateProfile = async () => {
  error.value = ''
  
  // Validate password fields if changing password
  if (form.value.password) {
    if (!form.value.current_password) {
      error.value = 'Current password is required to change password'
      return
    }
    if (form.value.password !== form.value.password_confirmation) {
      error.value = 'New passwords do not match'
      return
    }
    if (form.value.password.length < 8) {
      error.value = 'New password must be at least 8 characters'
      return
    }
  }

  isLoading.value = true

  try {
    const response = await $fetch(`${config.public.apiBase}/user/profile`, {
      method: 'PUT',
      headers: {
        'Authorization': `Bearer ${useCookie('auth-token').value}`,
        'Content-Type': 'application/json'
      },
      body: {
        name: form.value.name,
        email: form.value.email,
        phone: form.value.phone || null,
        current_password: form.value.current_password || undefined,
        password: form.value.password || undefined,
        password_confirmation: form.value.password_confirmation || undefined
      }
    })

    success('Profile Updated', 'Your profile has been updated successfully.')
    emit('updated')
    emit('close')
  } catch (err: any) {
    error.value = err.data?.message || 'Failed to update profile'
  } finally {
    isLoading.value = false
  }
}
</script> 