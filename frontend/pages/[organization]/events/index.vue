<template>
  <div class="min-h-screen bg-gray-100">
    <div class="py-10">
      <main>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <!-- Events Grid -->
          <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div v-for="event in organizationStore.events" :key="event.id" class="bg-white overflow-hidden shadow rounded-lg">
              <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900">{{ event.title }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ event.description }}</p>
                <div class="mt-4 space-y-2">
                  <p class="flex items-center text-sm text-gray-500">
                    <LocationMarkerIcon class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" />
                    {{ event.venue }}
                  </p>
                  <p class="flex items-center text-sm text-gray-500">
                    <CalendarIcon class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" />
                    {{ new Date(event.date).toLocaleString() }}
                  </p>
                  <p class="flex items-center text-sm text-gray-500">
                    <CurrencyDollarIcon class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" />
                    ${{ event.price }}
                  </p>
                  <p class="flex items-center text-sm text-gray-500">
                    <UsersIcon class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" />
                    {{ event.current_attendees }} / {{ event.max_attendees }} attendees
                  </p>
                </div>
                <div class="mt-6">
                  <button
                    @click="openRegistrationModal(event)"
                    :disabled="event.current_attendees >= event.max_attendees"
                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white"
                    :class="event.current_attendees >= event.max_attendees ? 'bg-gray-400 cursor-not-allowed' : 'bg-indigo-600 hover:bg-indigo-700'"
                  >
                    {{ event.current_attendees >= event.max_attendees ? 'Event Full' : 'Register Now' }}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>

    <!-- Registration Modal -->
    <div v-if="showModal" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showModal = false"></div>

        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
          <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
              Register for {{ selectedEvent?.title }}
            </h3>
            <div class="mt-4">
              <form @submit.prevent="handleRegistration" class="space-y-4">
                <div>
                  <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                  <input
                    type="text"
                    id="name"
                    v-model="registrationForm.name"
                    required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  />
                </div>
                <div>
                  <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                  <input
                    type="email"
                    id="email"
                    v-model="registrationForm.email"
                    required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  />
                </div>
                <div>
                  <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                  <input
                    type="tel"
                    id="phone"
                    v-model="registrationForm.phone"
                    required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  />
                </div>
                <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                  <button
                    type="submit"
                    :disabled="organizationStore.loading"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:col-start-2 sm:text-sm"
                  >
                    {{ organizationStore.loading ? 'Registering...' : 'Register' }}
                  </button>
                  <button
                    type="button"
                    @click="showModal = false"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:col-start-1 sm:text-sm"
                  >
                    Cancel
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Success/Error Toast -->
    <div v-if="toast.show" class="fixed bottom-0 inset-x-0 pb-2 sm:pb-5">
      <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
        <div :class="[
          'rounded-lg p-2 shadow-lg sm:p-3',
          toast.type === 'success' ? 'bg-green-600' : 'bg-red-600'
        ]">
          <div class="flex items-center justify-between flex-wrap">
            <div class="w-0 flex-1 flex items-center">
              <p class="ml-3 font-medium text-white truncate">
                <span>{{ toast.message }}</span>
              </p>
            </div>
            <div class="order-2 flex-shrink-0 sm:order-3 sm:ml-2">
              <button
                @click="toast.show = false"
                type="button"
                class="-mr-1 flex p-2 rounded-md hover:bg-opacity-20 hover:bg-black focus:outline-none focus:ring-2 focus:ring-white"
              >
                <XIcon class="h-5 w-5 text-white" />
              </button>
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
import { useOrganizationStore } from '~/stores/organization'
import { LocationMarkerIcon, CalendarIcon, CurrencyDollarIcon, UsersIcon, XIcon } from '@heroicons/vue/solid'

const route = useRoute()
const organizationStore = useOrganizationStore()

const organization = route.params.organization
const showModal = ref(false)
const selectedEvent = ref<any>(null)
const registrationForm = ref({
  name: '',
  email: '',
  phone: ''
})
const toast = ref({
  show: false,
  message: '',
  type: 'success'
})

const openRegistrationModal = (event: any) => {
  selectedEvent.value = event
  showModal.value = true
}

const handleRegistration = async () => {
  try {
    await organizationStore.registerForEvent(
      organization as string,
      selectedEvent.value.id,
      registrationForm.value
    )
    showModal.value = false
    toast.value = {
      show: true,
      message: 'Registration successful!',
      type: 'success'
    }
    registrationForm.value = { name: '', email: '', phone: '' }
    await organizationStore.fetchOrganizationBySlug(organization as string)
    if (organizationStore.currentOrganization) {
      await organizationStore.fetchOrganizationEvents(organizationStore.currentOrganization.id)
    }
  } catch (error) {
    toast.value = {
      show: true,
      message: 'Registration failed. Please try again.',
      type: 'error'
    }
  }
}

onMounted(async () => {
  await organizationStore.fetchOrganizationBySlug(organization as string)
  if (organizationStore.currentOrganization) {
    await organizationStore.fetchOrganizationEvents(organizationStore.currentOrganization.id)
  }
})

// Auto-hide toast after 5 seconds
watch(() => toast.value.show, (newValue) => {
  if (newValue) {
    setTimeout(() => {
      toast.value.show = false
    }, 5000)
  }
})
</script> 