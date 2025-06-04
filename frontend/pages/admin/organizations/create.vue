<template>
  <div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Header -->
      <div class="mb-8">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">Create Organization</h1>
            <p class="mt-2 text-gray-600">Add a new organization to the platform</p>
          </div>
          <NuxtLink to="/admin/dashboard" class="text-blue-600 hover:text-blue-800 flex items-center">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Dashboard
          </NuxtLink>
        </div>
      </div>

      <!-- Form -->
      <div class="bg-white rounded-xl shadow-sm p-8">
        <form @submit.prevent="createOrganization" class="space-y-6">
          <!-- Organization Name -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Organization Name</label>
            <input 
              v-model="form.name"
              type="text" 
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="Enter organization name"
            >
          </div>

          <!-- Slug -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">URL Slug</label>
            <div class="flex">
              <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                yoursite.com/
              </span>
              <input 
                v-model="form.slug"
                type="text" 
                required
                class="flex-1 px-3 py-2 border border-gray-300 rounded-r-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="organization-slug"
              >
            </div>
            <p class="mt-1 text-sm text-gray-500">This will be used in the URL for public events</p>
          </div>

          <!-- Email -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Email</label>
            <input 
              v-model="form.email"
              type="email" 
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="contact@organization.com"
            >
          </div>

          <!-- Phone -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
            <input 
              v-model="form.phone"
              type="tel" 
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="+1 (555) 123-4567"
            >
          </div>

          <!-- Address -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
            <textarea 
              v-model="form.address"
              rows="3"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="Organization address"
            ></textarea>
          </div>

          <!-- Admin User Details -->
          <div class="border-t pt-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Admin User</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Admin Name</label>
                <input 
                  v-model="form.admin_name"
                  type="text" 
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Admin full name"
                >
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Admin Email</label>
                <input 
                  v-model="form.admin_email"
                  type="email" 
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="admin@organization.com"
                >
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input 
                  v-model="form.admin_password"
                  type="password" 
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Secure password"
                >
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                <input 
                  v-model="form.admin_password_confirmation"
                  type="password" 
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Confirm password"
                >
              </div>
            </div>
          </div>

          <!-- Submit Button -->
          <div class="flex justify-end space-x-4 pt-6">
            <NuxtLink 
              to="/admin/dashboard"
              class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
            >
              Cancel
            </NuxtLink>
            <button
              type="submit"
              :disabled="isSubmitting"
              class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="isSubmitting" class="flex items-center">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Creating...
              </span>
              <span v-else>Create Organization</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useNotification } from '~/composables/useNotification'

definePageMeta({
  layout: 'admin',
  middleware: 'auth'
})

useHead({
  title: 'Create Organization - Admin Dashboard'
})

const config = useRuntimeConfig()
const router = useRouter()
const { success, error } = useNotification()

const isSubmitting = ref(false)

const form = ref({
  name: '',
  slug: '',
  email: '',
  phone: '',
  address: '',
  admin_name: '',
  admin_email: '',
  admin_password: '',
  admin_password_confirmation: ''
})

// Auto-generate slug from name
watch(() => form.value.name, (newName) => {
  if (newName && !form.value.slug) {
    form.value.slug = newName
      .toLowerCase()
      .replace(/[^a-z0-9]+/g, '-')
      .replace(/^-+|-+$/g, '')
  }
})

const createOrganization = async () => {
  if (form.value.admin_password !== form.value.admin_password_confirmation) {
    error('Password Mismatch', 'Please make sure both password fields match.')
    return
  }

  isSubmitting.value = true

  try {
    const response = await $fetch(`${config.public.apiBase}/admin/organizations`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${useCookie('auth-token').value}`
      },
      body: {
        name: form.value.name,
        slug: form.value.slug,
        email: form.value.email,
        phone: form.value.phone,
        address: form.value.address,
        admin_name: form.value.admin_name,
        admin_email: form.value.admin_email,
        admin_password: form.value.admin_password,
        admin_password_confirmation: form.value.admin_password_confirmation
      }
    })

    success(
      'Organization Created Successfully!', 
      `${form.value.name} has been created with admin user ${form.value.admin_name}.`,
      { duration: 5000 }
    )

    // Clear form
    form.value = {
      name: '',
      slug: '',
      email: '',
      phone: '',
      address: '',
      admin_name: '',
      admin_email: '',
      admin_password: '',
      admin_password_confirmation: ''
    }

    // Delay redirect to allow user to see success message
    setTimeout(() => {
      router.push('/admin/dashboard')
    }, 2000)

  } catch (err: any) {
    console.error('Error creating organization:', err)
    error(
      'Failed to Create Organization', 
      err.data?.message || 'An error occurred while creating the organization. Please try again.',
      { duration: 6000 }
    )
  } finally {
    isSubmitting.value = false
  }
}
</script> 