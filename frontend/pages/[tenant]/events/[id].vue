// frontend/pages/[tenant]/events/[id].vue
<template>
  <div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Loading State -->
      <div v-if="isLoading" class="flex justify-center items-center py-12">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="bg-red-50 p-4 rounded-md">
        <p class="text-red-700">{{ error }}</p>
        <CommonButton @click="fetchEvent" variant="outline" class="mt-2">
          Try Again
        </CommonButton>
      </div>

      <!-- Event Details -->
      <div v-else class="bg-white shadow-sm rounded-lg p-6 mb-8">
        <h1 class="text-3xl font-bold text-gray-900">{{ event.title }}</h1>
        <div class="mt-4 grid grid-cols-1 gap-6 sm:grid-cols-2">
          <div>
            <p class="text-gray-500 mt-2">{{ event.description }}</p>
            <div class="mt-4 space-y-2">
              <p class="flex items-center text-gray-600">
                <CalendarIcon class="h-5 w-5 mr-2" />
                {{ formatDate(event.date) }}
              </p>
              <p class="flex items-center text-gray-600">
                <MapPinIcon class="h-5 w-5 mr-2" />
                {{ event.venue }}
              </p>
              <p class="flex items-center text-gray-600">
                <CurrencyDollarIcon class="h-5 w-5 mr-2" />
                KSH {{ event.price.toLocaleString() }}
              </p>
              <p class="flex items-center text-gray-600">
                <UsersIcon class="h-5 w-5 mr-2" />
                {{ event.registeredAttendees }} / {{ event.maxAttendees }} spots filled
              </p>
            </div>
          </div>

          <!-- Registration Form -->
          <div class="bg-gray-50 p-6 rounded-lg">
            <h2 class="text-lg font-medium text-gray-900">Register for this event</h2>
            
            <!-- Show login prompt for non-authenticated users -->
            <div v-if="!authStore.isAuthenticated" class="mt-4">
              <p class="text-gray-600 mb-4">Please sign in to register for this event.</p>
              <div class="space-y-3">
                <NuxtLink :to="`/login?redirect=${encodeURIComponent(route.fullPath)}`">
                  <CommonButton variant="primary" class="w-full">Sign In</CommonButton>
                </NuxtLink>
                <NuxtLink :to="`/register?redirect=${encodeURIComponent(route.fullPath)}`">
                  <CommonButton variant="outline" class="w-full">Create Account</CommonButton>
                </NuxtLink>
              </div>
            </div>

            <!-- Registration form for authenticated users -->
            <form v-else @submit.prevent="handleRegistration" class="mt-4 space-y-4">
              <!-- Prefill form with user data if available -->
              <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                <input
                  id="name"
                  v-model="form.name"
                  type="text"
                  required
                  :disabled="isRegistering"
                  :placeholder="authStore.user?.name || ''"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm disabled:bg-gray-100 disabled:cursor-not-allowed"
                />
                <p v-if="v$.form.name.$error" class="mt-1 text-sm text-red-600">
                  {{ v$.form.name.$errors[0].$message }}
                </p>
              </div>

              <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input
                  id="email"
                  v-model="form.email"
                  type="email"
                  required
                  :disabled="isRegistering"
                  :placeholder="authStore.user?.email || ''"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm disabled:bg-gray-100 disabled:cursor-not-allowed"
                />
                <p v-if="v$.form.email.$error" class="mt-1 text-sm text-red-600">
                  {{ v$.form.email.$errors[0].$message }}
                </p>
              </div>

              <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                <input
                  id="phone"
                  v-model="form.phone"
                  type="tel"
                  required
                  :disabled="isRegistering"
                  :placeholder="authStore.user?.phone ?? ''"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm disabled:bg-gray-100 disabled:cursor-not-allowed"
                />
                <p v-if="v$.form.phone.$error" class="mt-1 text-sm text-red-600">
                  {{ v$.form.phone.$errors[0].$message }}
                </p>
              </div>

              <CommonButton
                type="submit"
                class="w-full"
                :disabled="isRegistering || event.registeredAttendees >= event.maxAttendees || v$.$invalid"
              >
                {{ isRegistering ? 'Registering...' : 'Register Now' }}
              </CommonButton>

              <p v-if="event.registeredAttendees >= event.maxAttendees" class="text-sm text-red-600 text-center">
                This event is fully booked
              </p>
            </form>

            <div v-if="registrationError" class="mt-4 text-sm text-red-600">
              {{ registrationError }}
            </div>
            <div v-if="registrationSuccess" class="mt-4 text-sm text-green-600">
              {{ registrationSuccess }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useVuelidate } from '@vuelidate/core'
import { required, email, helpers } from '@vuelidate/validators'
import {
  CalendarIcon,
  MapPinIcon,
  CurrencyDollarIcon,
  UsersIcon
} from '@heroicons/vue/24/outline'
import CommonButton from '@/components/Common/Button.vue'
import { eventService, type Event } from '@/services/events'
import { useAuthStore } from '@/stores/auth'

const route = useRoute()
const tenant = route.params.tenant as string
const eventId = route.params.id as string

const event = ref<Event>({
  id: 0,
  title: '',
  description: '',
  date: new Date().toISOString(),
  venue: '',
  price: 0,
  maxAttendees: 0,
  registeredAttendees: 0
})

const form = ref({
  name: '',
  email: '',
  phone: ''
})

const isLoading = ref(false)
const isRegistering = ref(false)
const error = ref<string | null>(null)
const registrationError = ref<string | null>(null)
const registrationSuccess = ref<string | null>(null)

const authStore = useAuthStore()

// Form validation rules
const rules = {
  form: {
    name: { required: helpers.withMessage('Name is required', required) },
    email: { 
      required: helpers.withMessage('Email is required', required),
      email: helpers.withMessage('Please enter a valid email address', email)
    },
    phone: { 
      required: helpers.withMessage('Phone number is required', required),
      phoneFormat: helpers.withMessage('Please enter a valid phone number', helpers.regex(/^\+?[\d\s-]{10,}$/))
    }
  }
}

const v$ = useVuelidate(rules, { form })

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('en-US', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: 'numeric',
    minute: 'numeric'
  })
}

const fetchEvent = async () => {
  isLoading.value = true
  error.value = null
  
  try {
    event.value = await eventService.getEvent(tenant, eventId)
  } catch (e) {
    error.value = e instanceof Error ? e.message : 'Failed to load event details'
  } finally {
    isLoading.value = false
  }
}

const handleRegistration = async () => {
  registrationError.value = null
  registrationSuccess.value = null
  
  const result = await v$.value.$validate()
  if (!result) return

  isRegistering.value = true
  
  try {
    await eventService.registerForEvent(tenant, eventId, form.value)
    registrationSuccess.value = 'Registration successful! Check your email for confirmation.'
    form.value = { name: '', email: '', phone: '' }
    // Refresh event details to update attendance count
    await fetchEvent()
  } catch (e) {
    registrationError.value = e instanceof Error ? e.message : 'Failed to register. Please try again.'
  } finally {
    isRegistering.value = false
  }
}

// Initialize form with user data if available
onMounted(() => {
  fetchEvent()
  if (authStore.isAuthenticated && authStore.user) {
    form.value = {
      name: authStore.user.name || '',
      email: authStore.user.email || '',
      phone: authStore.user.phone ?? ''
    }
  }
})
</script>

<style scoped>
/* Component-specific styles can be added here if needed */
</style>