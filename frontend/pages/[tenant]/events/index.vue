<template>
  <div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold">Events</h1>
      <CommonButton @click="refreshEvents" :disabled="loading">
        {{ loading ? 'Loading...' : 'Refresh' }}
      </CommonButton>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex justify-center items-center py-12">
      <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="bg-red-50 p-4 rounded-md">
      <p class="text-red-700">{{ error }}</p>
      <CommonButton @click="refreshEvents" variant="outline" class="mt-2">
        Try Again
      </CommonButton>
    </div>

    <!-- Events Grid -->
    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="event in events" :key="event.id" class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-6">
          <h2 class="text-xl font-semibold mb-2">{{ event.title }}</h2>
          <p class="text-gray-600 mb-4 line-clamp-2">{{ event.description }}</p>
          <div class="space-y-2">
            <p class="flex items-center text-gray-600 text-sm">
              <CalendarIcon class="h-4 w-4 mr-2" />
              {{ formatDate(event.date) }}
            </p>
            <p class="flex items-center text-gray-600 text-sm">
              <MapPinIcon class="h-4 w-4 mr-2" />
              {{ event.venue }}
            </p>
            <p class="flex items-center text-gray-600 text-sm">
              <CurrencyDollarIcon class="h-4 w-4 mr-2" />
              ${{ event.price }}
            </p>
            <p class="flex items-center text-gray-600 text-sm">
              <UsersIcon class="h-4 w-4 mr-2" />
              {{ event.registeredAttendees }}/{{ event.maxAttendees }} spots
            </p>
          </div>
          <div class="mt-4">
            <NuxtLink :to="`/${route.params.tenant}/events/${event.id}`">
              <CommonButton variant="primary" class="w-full">View Details</CommonButton>
            </NuxtLink>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="events.length === 0" class="col-span-full text-center py-12">
        <p class="text-gray-500">No events found.</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { CalendarIcon, MapPinIcon, CurrencyDollarIcon, UsersIcon } from '@heroicons/vue/24/outline'
import CommonButton from '@/components/Common/Button.vue'
import { eventService, type Event } from '@/services/events'

definePageMeta({
  layout: 'default'
})

const route = useRoute()
const tenant = route.params.tenant as string
const events = ref<Event[]>([])
const loading = ref(true)
const error = ref<string | null>(null)

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

const fetchEvents = async () => {
  try {
    loading.value = true
    events.value = await eventService.getPublicEvents(tenant)
  } catch (err) {
    error.value = 'Failed to load events. Please try again later.'
    console.error('Error fetching events:', err)
  } finally {
    loading.value = false
  }
}

const refreshEvents = () => {
  fetchEvents()
}

onMounted(() => {
  fetchEvents()
})
</script>
