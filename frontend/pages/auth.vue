<template>
  <!-- Hero Section with Background -->
  <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 dark:from-slate-900 dark:via-slate-800 dark:to-indigo-900 relative overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
      <div class="absolute top-0 right-0 -mt-20 -mr-20 md:-mt-28 md:-mr-28 w-96 h-96 bg-gradient-to-tr from-blue-100/80 via-indigo-50/70 to-purple-100/80 dark:from-blue-900/50 dark:via-indigo-900/30 dark:to-purple-900/30 rounded-full opacity-60 blur-3xl animate-float"></div>
      <div class="absolute bottom-0 left-0 -mb-20 -ml-20 md:-mb-28 md:-ml-28 w-80 h-80 bg-gradient-to-br from-pink-100/70 via-red-50/60 to-orange-100/70 dark:from-pink-900/50 dark:via-red-900/30 dark:to-orange-900/30 rounded-full opacity-50 blur-3xl animate-float-delayed"></div>
      <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>
    </div>

    <!-- Navigation Header -->
    <header class="relative z-50 bg-white/80 dark:bg-slate-900/80 backdrop-blur-lg border-b border-slate-200/50 dark:border-slate-800/50">
      <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <!-- Left side - Logo -->
          <div class="flex items-center">
            <NuxtLink to="/" class="flex items-center flex-shrink-0">
              <div class="w-8 h-8 rounded bg-blue-600 dark:bg-blue-500 flex items-center justify-center mr-2">
                <span class="text-lg font-bold text-white">S</span>
              </div>
              <span class="text-xl font-bold text-gray-900 dark:text-white">Solutechify</span>
            </NuxtLink>
          </div>

          <!-- Right side - Home link -->
          <div class="flex items-center space-x-4">
            <NuxtLink 
              to="/" 
              class="text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200"
            >
              ← Back to Home
            </NuxtLink>
          </div>
        </div>
      </nav>
    </header>

    <!-- Main Content -->
    <div class="relative z-10 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 min-h-[calc(100vh-4rem)]">
      <div class="max-w-md w-full space-y-8">
        <!-- Hero Header -->
        <div class="text-center">
          <div class="mb-8">
            <div class="inline-flex items-center space-x-2 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors mb-6">
              <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2L2 7v10c0 5.55 3.84 9.99 9 11 5.16-1.01 9-5.45 9-11V7l-10-5z"/>
              </svg>
              <span class="text-3xl font-bold">SolutechifyEvents</span>
            </div>
            <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white sm:text-5xl animate-fade-in">
              <span class="block">{{ isLogin ? 'Welcome back!' : 'Join us today' }}</span>
              <span class="block text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-400 dark:to-indigo-400 animate-gradient">
                {{ isLogin ? 'Sign in to continue' : 'Start your journey' }}
              </span>
            </h1>
            <p class="mt-3 text-base text-gray-600 dark:text-slate-300 sm:mt-5 sm:text-lg animate-fade-in-delayed">
              {{ isLogin 
                ? 'Access your dashboard and manage your events' 
                : 'Create your account and start managing events like a pro' 
              }}
            </p>
          </div>
        </div>

        <!-- Auth Form Card -->
        <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100/20 dark:border-slate-700/20 p-8 space-y-6 animate-fade-in-delayed-2">
          <!-- Login/Register Toggle -->
          <div class="flex bg-gray-100 dark:bg-slate-700 rounded-lg p-1">
            <button
              @click="isLogin = true"
              :class="[
                'flex-1 text-center py-2 px-4 rounded-md text-sm font-medium transition-all duration-200',
                isLogin 
                  ? 'bg-white dark:bg-slate-600 text-blue-600 dark:text-blue-400 shadow-sm' 
                  : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'
              ]"
            >
              Sign In
            </button>
            <button
              @click="isLogin = false"
              :class="[
                'flex-1 text-center py-2 px-4 rounded-md text-sm font-medium transition-all duration-200',
                !isLogin 
                  ? 'bg-white dark:bg-slate-600 text-blue-600 dark:text-blue-400 shadow-sm' 
                  : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'
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
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { useAuthStore } from '~/stores/auth'
import { useRouter } from 'vue-router'
import type { RegisterData } from '~/types'
import type { Organization } from '@/types'

// Set layout
definePageMeta({
  layout: 'auth'
})

useHead({
  title: 'Authentication - Solutechify Events',
  meta: [
    { name: 'description', content: 'Sign in or create your account to start managing events with Solutechify.' }
  ]
})

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

// Handle form submission
const handleSubmit = async () => {
  if (!validateForm()) {
    return
  }

  try {
    if (isLogin.value) {
      // Login - auth store will handle success notification and redirect
      await authStore.login(formData.value.email, formData.value.password)
    } else {
      // Register - auth store will handle success notification and redirect
      const userData: RegisterData = {
        name: formData.value.name,
        email: formData.value.email,
        password: formData.value.password,
        role: userType.value === 'hoster' ? 'organizer' : 'user',
        phone: formData.value.phone
      }

      if (userType.value === 'hoster') {
        if (organizationOption.value === 'existing' && formData.value.organization_id) {
          userData.organization_id = formData.value.organization_id
        } else if (organizationOption.value === 'new') {
          userData.organization_name = formData.value.organization_name
          userData.organization_email = formData.value.organization_email
        }
      }

      await authStore.register(userData)
    }
    // Note: Auth store handles success notifications and redirects automatically
  } catch (error) {
    // Auth store handles error notifications
    console.error('Authentication error:', error)
  }
}

// Reset form when switching between login/signup
watch(isLogin, () => {
  formData.value = {
    name: '',
    email: '',
    password: '',
    confirmPassword: '',
    organization_id: undefined,
    organization_name: '',
    organization_email: '',
    acceptTerms: false,
    phone: ''
  }
  // Clear error manually since clearError method doesn't exist
  authStore.error = null
})

// Reset form when changing user type
watch(userType, () => {
  formData.value.organization_id = undefined
  formData.value.organization_name = ''
  formData.value.organization_email = ''
  organizationOption.value = 'existing'
})
</script>

<style scoped>
/* Animation styles similar to the homepage hero */
.animate-fade-in {
  opacity: 0;
  animation: fadeIn 0.8s ease-out forwards;
}

.animate-fade-in-delayed {
  opacity: 0;
  animation: fadeIn 0.8s ease-out 0.3s forwards;
}

.animate-fade-in-delayed-2 {
  opacity: 0;
  animation: fadeIn 0.8s ease-out 0.6s forwards;
}

.animate-float {
  animation: float 6s ease-in-out infinite;
}

.animate-float-delayed {
  animation: float 6s ease-in-out 3s infinite;
}

.animate-gradient {
  animation: gradient 3s ease-in-out infinite;
}

@keyframes fadeIn {
  from { 
    opacity: 0; 
    transform: translateY(20px); 
  }
  to { 
    opacity: 1; 
    transform: translateY(0); 
  }
}

@keyframes float {
  0%, 100% { 
    transform: translateY(0px) rotate(0deg); 
  }
  50% { 
    transform: translateY(-20px) rotate(10deg); 
  }
}

@keyframes gradient {
  0%, 100% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
}

.bg-grid-pattern {
  background-image: linear-gradient(to right, rgba(99, 102, 241, 0.1) 1px, transparent 1px),
                    linear-gradient(to bottom, rgba(99, 102, 241, 0.1) 1px, transparent 1px);
  background-size: 24px 24px;
}

/* Dark mode adjustments */
@media (prefers-color-scheme: dark) {
  .bg-grid-pattern {
    background-image: linear-gradient(to right, rgba(99, 102, 241, 0.2) 1px, transparent 1px),
                      linear-gradient(to bottom, rgba(99, 102, 241, 0.2) 1px, transparent 1px);
  }
}

/* Enhanced form styling */
.auth-form-card {
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.2);
}

/* Focus states for better accessibility */
input:focus, select:focus {
  transform: translateY(-1px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

/* Smooth transitions for user type cards */
input[type="radio"] + label {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

input[type="radio"]:checked + label {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

/* Button hover effects */
button:hover:not(:disabled) {
  transform: translateY(-1px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

button:active:not(:disabled) {
  transform: translateY(0);
}
</style> 