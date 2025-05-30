<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <!-- Header -->
      <div class="text-center">
        <NuxtLink to="/" class="inline-flex items-center space-x-2 text-blue-600 hover:text-blue-800 transition-colors">
          <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2L2 7v10c0 5.55 3.84 9.99 9 11 5.16-1.01 9-5.45 9-11V7l-10-5z"/>
          </svg>
          <span class="text-2xl font-bold">SolutechifyEvents</span>
        </NuxtLink>
        <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
          {{ isLogin ? 'Welcome back!' : 'Join us today' }}
        </h2>
        <p class="mt-2 text-sm text-gray-600">
          {{ isLogin ? 'Sign in to your account' : 'Create your account and start managing events' }}
        </p>
      </div>

      <!-- Auth Form Card -->
      <div class="bg-white rounded-2xl shadow-xl p-8 space-y-6">
        <!-- Login/Register Toggle -->
        <div class="flex bg-gray-100 rounded-lg p-1">
          <button
            @click="isLogin = true"
            :class="[
              'flex-1 text-center py-2 px-4 rounded-md text-sm font-medium transition-all duration-200',
              isLogin 
                ? 'bg-white text-blue-600 shadow-sm' 
                : 'text-gray-500 hover:text-gray-700'
            ]"
          >
            Sign In
          </button>
          <button
            @click="isLogin = false"
            :class="[
              'flex-1 text-center py-2 px-4 rounded-md text-sm font-medium transition-all duration-200',
              !isLogin 
                ? 'bg-white text-blue-600 shadow-sm' 
                : 'text-gray-500 hover:text-gray-700'
            ]"
          >
            Sign Up
          </button>
        </div>

        <!-- User Type Toggle -->
        <div class="space-y-3">
          <label class="text-sm font-medium text-gray-700">I want to:</label>
          <div class="grid grid-cols-2 gap-3">
            <div>
              <input
                type="radio"
                id="attendee"
                value="attendee"
                v-model="userType"
                class="sr-only"
              />
              <label
                for="attendee"
                :class="[
                  'flex flex-col items-center p-4 border-2 rounded-lg cursor-pointer transition-all duration-200',
                  userType === 'attendee'
                    ? 'border-blue-500 bg-blue-50 text-blue-700'
                    : 'border-gray-200 hover:border-gray-300 text-gray-600'
                ]"
              >
                <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span class="text-sm font-medium">Attend Events</span>
                <span class="text-xs text-center mt-1">Join and register for events</span>
              </label>
            </div>
            <div>
              <input
                type="radio"
                id="hoster"
                value="hoster"
                v-model="userType"
                class="sr-only"
              />
              <label
                for="hoster"
                :class="[
                  'flex flex-col items-center p-4 border-2 rounded-lg cursor-pointer transition-all duration-200',
                  userType === 'hoster'
                    ? 'border-purple-500 bg-purple-50 text-purple-700'
                    : 'border-gray-200 hover:border-gray-300 text-gray-600'
                ]"
              >
                <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H9m0 0H5m0 0H3m2 0v-8a2 2 0 012-2h4a2 2 0 012 2v8"></path>
                </svg>
                <span class="text-sm font-medium">Host Events</span>
                <span class="text-xs text-center mt-1">Create and manage events</span>
              </label>
            </div>
          </div>
        </div>

        <!-- Organization Selection (only for hosters during signup) -->
        <div v-if="!isLogin && userType === 'hoster'" class="space-y-2">
          <label class="block text-sm font-medium text-gray-700">Organization</label>
          <div class="space-y-3">
            <div>
              <input
                type="radio"
                id="existing-org"
                value="existing"
                v-model="organizationOption"
                class="sr-only"
              />
              <label
                for="existing-org"
                :class="[
                  'flex items-center p-3 border rounded-lg cursor-pointer transition-all duration-200',
                  organizationOption === 'existing'
                    ? 'border-blue-500 bg-blue-50'
                    : 'border-gray-200 hover:border-gray-300'
                ]"
              >
                <span class="text-sm font-medium">Join existing organization</span>
              </label>
            </div>
            <div>
              <input
                type="radio"
                id="new-org"
                value="new"
                v-model="organizationOption"
                class="sr-only"
              />
              <label
                for="new-org"
                :class="[
                  'flex items-center p-3 border rounded-lg cursor-pointer transition-all duration-200',
                  organizationOption === 'new'
                    ? 'border-blue-500 bg-blue-50'
                    : 'border-gray-200 hover:border-gray-300'
                ]"
              >
                <span class="text-sm font-medium">Create new organization</span>
              </label>
            </div>
          </div>

          <!-- Organization Dropdown (for existing) -->
          <div v-if="organizationOption === 'existing'" class="mt-3">
            <select
              v-model="formData.organization_id"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              required
            >
              <option value="">Select an organization</option>
              <option v-for="org in organizations" :key="org.id" :value="org.id">
                {{ org.name }}
              </option>
            </select>
          </div>

          <!-- New Organization Fields -->
          <div v-if="organizationOption === 'new'" class="space-y-3 mt-3">
            <div>
              <label class="block text-sm font-medium text-gray-700">Organization Name</label>
              <input
                v-model="formData.organization_name"
                type="text"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Enter organization name"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Organization Email</label>
              <input
                v-model="formData.organization_email"
                type="email"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="contact@organization.com"
              />
            </div>
          </div>
        </div>

        <!-- Form Fields -->
        <form @submit.prevent="handleSubmit" class="space-y-4">
          <!-- Name (only for signup) -->
          <div v-if="!isLogin">
            <label class="block text-sm font-medium text-gray-700">Full Name</label>
            <input
              v-model="formData.name"
              type="text"
              required
              class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
              placeholder="Enter your full name"
            />
          </div>

          <!-- Email -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Email Address</label>
            <input
              v-model="formData.email"
              type="email"
              required
              class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
              placeholder="Enter your email"
            />
          </div>

          <!-- Password -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Password</label>
            <div class="relative">
              <input
                v-model="formData.password"
                :type="showPassword ? 'text' : 'password'"
                required
                class="w-full px-3 py-3 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                :placeholder="isLogin ? 'Enter your password' : 'Create a password'"
              />
              <button
                type="button"
                @click="showPassword = !showPassword"
                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
              >
                <svg v-if="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                </svg>
                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
              </button>
            </div>
          </div>

          <!-- Confirm Password (only for signup) -->
          <div v-if="!isLogin">
            <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
            <input
              v-model="formData.confirmPassword"
              type="password"
              required
              class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
              placeholder="Confirm your password"
            />
          </div>

          <!-- Terms (only for signup) -->
          <div v-if="!isLogin" class="flex items-start">
            <input
              v-model="formData.acceptTerms"
              type="checkbox"
              required
              class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
            />
            <label class="ml-2 text-sm text-gray-600">
              I agree to the 
              <a href="#" class="text-blue-600 hover:text-blue-800">Terms of Service</a> 
              and 
              <a href="#" class="text-blue-600 hover:text-blue-800">Privacy Policy</a>
            </label>
          </div>

          <!-- Error Message -->
          <div v-if="error" class="bg-red-50 border border-red-200 rounded-lg p-3">
            <div class="flex">
              <svg class="w-5 h-5 text-red-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <p class="text-sm text-red-600">{{ error }}</p>
            </div>
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            :disabled="loading"
            :class="[
              'w-full py-3 px-4 border border-transparent rounded-lg text-sm font-medium text-white transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2',
              userType === 'attendee'
                ? 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500'
                : 'bg-purple-600 hover:bg-purple-700 focus:ring-purple-500',
              loading ? 'opacity-50 cursor-not-allowed' : ''
            ]"
          >
            <span v-if="loading" class="flex items-center justify-center">
              <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Processing...
            </span>
            <span v-else>
              {{ isLogin ? 'Sign In' : `Sign Up as ${userType === 'attendee' ? 'Attendee' : 'Event Hoster'}` }}
            </span>
          </button>
        </form>

        <!-- Footer Links -->
        <div class="text-center space-y-2">
          <div v-if="isLogin" class="text-sm">
            <a href="#" class="text-blue-600 hover:text-blue-800">Forgot your password?</a>
          </div>
          <div class="text-sm text-gray-600">
            <NuxtLink to="/" class="text-blue-600 hover:text-blue-800">
              ← Back to Home
            </NuxtLink>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useAuthStore } from '~/stores/auth'
import { useRouter } from 'vue-router'
import type { RegisterData } from '~/types'
import type { Organization } from '@/types'

const config = useRuntimeConfig()
const router = useRouter()
const authStore = useAuthStore()

// Form state
const isLogin = ref(true)
const userType = ref('attendee')
const organizationOption = ref<'existing' | 'new'>('existing')
const showPassword = ref(false)
const loading = computed(() => authStore.loading)
const error = computed(() => authStore.error)

// Form fields
const formData = ref({
  name: '',
  email: '',
  password: '',
  confirmPassword: '',
  organization_id: undefined as number | undefined,
  organization_name: '',
  organization_email: '',
  acceptTerms: false,
  phone: ''
})

// Form validation
const validationErrors = ref({
  name: '',
  email: '',
  password: '',
  confirmPassword: ''
})

const validateForm = () => {
  let isValid = true
  validationErrors.value = {
    name: '',
    email: '',
    password: '',
    confirmPassword: ''
  }

  if (!isLogin.value && !formData.value.name) {
    validationErrors.value.name = 'Name is required'
    isValid = false
  }

  if (!formData.value.email) {
    validationErrors.value.email = 'Email is required'
    isValid = false
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(formData.value.email)) {
    validationErrors.value.email = 'Invalid email format'
    isValid = false
  }

  if (!formData.value.password) {
    validationErrors.value.password = 'Password is required'
    isValid = false
  } else if (formData.value.password.length < 8) {
    validationErrors.value.password = 'Password must be at least 8 characters'
    isValid = false
  }

  if (!isLogin.value && formData.value.password !== formData.value.confirmPassword) {
    validationErrors.value.confirmPassword = 'Passwords do not match'
    isValid = false
  }

  return isValid
}

// Fetch organizations for hoster signup
const { data: organizations } = await useFetch<Organization[]>('/organizations', {
  baseURL: config.public.apiBase,
  server: false
})

// Methods
const handleSubmit = async () => {
  if (!validateForm()) return

  try {
    if (isLogin.value) {
      await authStore.login(formData.value.email, formData.value.password)
    } else {
      const registerData: RegisterData = {
        name: formData.value.name,
        email: formData.value.email,
        password: formData.value.password,
        role: userType.value === 'hoster' ? 'organizer' : 'user',
        phone: formData.value.phone || undefined
      }

      // Add organization data for hosters
      if (userType.value === 'hoster') {
        if (organizationOption.value === 'existing' && formData.value.organization_id) {
          registerData.organization_id = Number(formData.value.organization_id)
        } else {
          registerData.organization_name = formData.value.organization_name
          registerData.organization_email = formData.value.organization_email
        }
      }

      await authStore.register(registerData)
    }
    
    // Redirect based on user role
    if (authStore.isAdmin) {
      router.push('/admin/dashboard')
    } else {
      router.push('/dashboard')
    }
  } catch (err) {
    // Error is handled by the store
    console.error('Authentication error:', err)
  }
}

// SEO
useHead({
  title: isLogin.value ? 'Sign In - SolutechifyEvents' : 'Sign Up - SolutechifyEvents',
  meta: [
    { 
      name: 'description', 
      content: isLogin.value 
        ? 'Sign in to your SolutechifyEvents account' 
        : 'Create a new account on SolutechifyEvents'
    }
  ]
})
</script> 