<template>
  <div class="min-h-screen bg-gray-100">
    <div class="py-10">
      <header>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h1 class="text-3xl font-bold leading-tight text-gray-900">Events</h1>
        </div>
      </header>
      <main>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <!-- Create Event Form -->
          <div class="bg-white shadow sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
              <h3 class="text-lg leading-6 font-medium text-gray-900">Create New Event</h3>
              <div class="mt-5">
                <form @submit.prevent="createNewEvent" class="space-y-4">
                  <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                    <input
                      type="text"
                      id="title"
                      v-model="newEvent.title"
                      required
                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    />
                  </div>
                  <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea
                      id="description"
                      v-model="newEvent.description"
                      rows="3"
                      required
                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    ></textarea>
                  </div>
                  <div>
                    <label for="venue" class="block text-sm font-medium text-gray-700">Venue</label>
                    <input
                      type="text"
                      id="venue"
                      v-model="newEvent.venue"
                      required
                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    />
                  </div>
                  <div>
                    <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                    <input
                      type="datetime-local"
                      id="date"
                      v-model="newEvent.date"
                      required
                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    />
                  </div>
                  <div>
                    <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                    <input
                      type="number"
                      id="price"
                      v-model="newEvent.price"
                      required
                      min="0"
                      step="0.01"
                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    />
                  </div>
                  <div>
                    <label for="max_attendees" class="block text-sm font-medium text-gray-700">Maximum Attendees</label>
                    <input
                      type="number"
                      id="max_attendees"
                      v-model="newEvent.max_attendees"
                      required
                      min="1"
                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    />
                  </div>
                  <button
                    type="submit"
                    :disabled="organizationStore.loading"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                  >
                    {{ organizationStore.loading ? 'Creating...' : 'Create Event' }}
                  </button>
                </form>
              </div>
            </div>
          </div>

          <!-- Events List -->
          <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul role="list" class="divide-y divide-gray-200">
              <li v-for="event in organizationStore.events" :key="event.id">
                <div class="px-4 py-4 sm:px-6">
                  <div class="flex items-center justify-between">
                    <div>
                      <h3 class="text-lg font-medium text-gray-900">{{ event.title }}</h3>
                      <p class="mt-1 text-sm text-gray-500">{{ event.description }}</p>
                      <div class="mt-2 sm:flex sm:justify-between">
                        <div class="sm:flex">
                          <p class="flex items-center text-sm text-gray-500">
                            <LocationMarkerIcon class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" />
                            {{ event.venue }}
                          </p>
                          <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                            <CalendarIcon class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" />
                            {{ new Date(event.date).toLocaleString() }}
                          </p>
                        </div>
                        <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                          <UsersIcon class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" />
                          {{ event.current_attendees }} / {{ event.max_attendees }} attendees
                        </div>
                      </div>
                    </div>
                    <div class="ml-5 flex-shrink-0">
                      <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium"
                        :class="event.current_attendees >= event.max_attendees ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'"
                      >
                        {{ event.current_attendees >= event.max_attendees ? 'Full' : 'Available' }}
                      </span>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useOrganizationStore } from '~/stores/organization'
import { LocationMarkerIcon, CalendarIcon, UsersIcon } from '@heroicons/vue/solid'

const route = useRoute()
const organizationStore = useOrganizationStore()

const newEvent = ref({
  title: '',
  description: '',
  venue: '',
  date: '',
  price: 0,
  max_attendees: 1
})

const createNewEvent = async () => {
  try {
    await organizationStore.createEvent(
      organizationStore.currentOrganization?.id as number,
      newEvent.value
    )
    newEvent.value = {
      title: '',
      description: '',
      venue: '',
      date: '',
      price: 0,
      max_attendees: 1
    }
  } catch (error) {
    console.error('Failed to create event:', error)
  }
}

onMounted(async () => {
  const slug = route.params.slug as string
  await organizationStore.fetchOrganizationBySlug(slug)
  if (organizationStore.currentOrganization) {
    await organizationStore.fetchOrganizationEvents(organizationStore.currentOrganization.id)
  }
})
</script> 