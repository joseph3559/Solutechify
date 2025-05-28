<template>
  <div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="text-center">
        <h1 class="text-4xl font-bold tracking-tight text-gray-900">
          {{ tenant }} Events
        </h1>
        <p class="mt-3 text-lg text-gray-500">
          Browse and register for upcoming events.
        </p>
        <div class="mt-8">
          <NuxtLink :to="`/${tenant}/events`">
            <CommonButton size="lg">View All Events</CommonButton>
          </NuxtLink>
        </div>
      </div>

      <!-- Featured Events Section -->
      <div class="mt-16">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Featured Events</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="event in featuredEvents" :key="event.id" 
               class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6">
              <h3 class="text-xl font-semibold mb-2">{{ event.title }}</h3>
              <p class="text-gray-600 mb-4 line-clamp-2">{{ event.description }}</p>
              <div class="flex items-center justify-between mt-4">
                <span class="text-primary font-medium">KSH {{ event.price.toLocaleString() }}</span>
                <NuxtLink :to="`/${tenant}/events/${event.id}`">
                  <CommonButton variant="outline" size="sm">Learn More</CommonButton>
                </NuxtLink>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Stats Section -->
      <div class="mt-16">
        <dl class="grid grid-cols-1 gap-5 sm:grid-cols-3">
          <div v-for="stat in stats" :key="stat.name" 
               class="px-4 py-5 bg-white shadow rounded-lg overflow-hidden sm:p-6">
            <dt class="text-sm font-medium text-gray-500 truncate">{{ stat.name }}</dt>
            <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ stat.value }}</dd>
          </div>
        </dl>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRoute } from 'vue-router'
import CommonButton from '@/components/Common/Button.vue'

const route = useRoute()
const tenant = route.params.tenant

interface Event {
  id: number
  title: string
  description: string
  price: number
}

const featuredEvents = ref<Event[]>([
  {
    id: 1,
    title: 'Tech Conference 2024',
    description: 'Join us for the biggest tech conference of the year.',
    price: 29999
  },
  {
    id: 2,
    title: 'Startup Workshop',
    description: 'Learn how to launch and grow your startup.',
    price: 14999
  },
  {
    id: 3,
    title: 'AI & ML Symposium',
    description: 'Explore the latest in AI and ML.',
    price: 19999
  }
])

const stats = ref([
  { name: 'Total Events', value: '12' },
  { name: 'Upcoming Events', value: '8' },
  { name: 'Registered Attendees', value: '1.2k' }
])
</script>
