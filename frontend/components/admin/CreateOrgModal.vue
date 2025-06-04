<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-lg w-full mx-4">
      <div class="p-6">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-xl font-semibold text-gray-900">Create New Organization</h2>
          <button
            @click="$emit('close')"
            class="text-gray-400 hover:text-gray-600"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <form @submit.prevent="createOrganization" class="space-y-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Organization Name *</label>
            <input
              v-model="form.name"
              type="text"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="Enter organization name"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Slug *</label>
            <input
              v-model="form.slug"
              type="text"
              required
              pattern="[a-z0-9-]+"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="organization-slug"
            />
            <p class="text-xs text-gray-500 mt-1">URL-friendly identifier (lowercase letters, numbers, and hyphens only)</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea
              v-model="form.description"
              rows="3"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="Describe the organization..."
            ></textarea>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Website</label>
            <input
              v-model="form.website"
              type="url"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="https://example.com"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Contact Email</label>
            <input
              v-model="form.contact_email"
              type="email"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="contact@organization.com"
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
              class="px-6 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="isLoading">Creating...</span>
              <span v-else>Create Organization</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import { useNotification } from '~/composables/useNotification'

const emit = defineEmits<{
  close: []
  created: []
}>()

const config = useRuntimeConfig()
const { success, error: showError } = useNotification()

const isLoading = ref(false)
const error = ref('')

const form = ref({
  name: '',
  slug: '',
  description: '',
  website: '',
  contact_email: ''
})

// Auto-generate slug from name
watch(() => form.value.name, (newName) => {
  if (newName && !form.value.slug) {
    form.value.slug = newName
      .toLowerCase()
      .replace(/[^a-z0-9\s-]/g, '')
      .replace(/\s+/g, '-')
      .replace(/-+/g, '-')
      .trim()
  }
})

const createOrganization = async () => {
  error.value = ''
  isLoading.value = true

  try {
    await $fetch('/admin/organizations', {
      method: 'POST',
      baseURL: config.public.apiBase,
      headers: {
        'Authorization': `Bearer ${useCookie('auth-token').value}`,
        'Content-Type': 'application/json'
      },
      body: form.value
    })

    success('Organization Created', 'The organization has been created successfully!')
    emit('created')
  } catch (err: any) {
    error.value = err.data?.message || 'Failed to create organization'
  } finally {
    isLoading.value = false
  }
}
</script> 