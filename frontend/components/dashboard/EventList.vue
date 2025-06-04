<template>
  <div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-200">
      <h2 class="text-xl font-semibold text-gray-900">{{ title }}</h2>
    </div>
    <div class="p-6">
      <div v-if="events?.length" class="space-y-4">
        <div
          v-for="registration in events"
          :key="registration.id"
          class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors"
        >
          <div class="flex items-center justify-between">
            <div class="flex-1">
              <div class="flex items-start justify-between">
                <div class="flex-1">
                  <h3 class="text-lg font-medium text-gray-900">{{ registration.event.title }}</h3>
                  <p class="text-sm text-gray-600 mt-1">{{ registration.event.organization?.name }}</p>
                  <div class="mt-2 space-y-1">
                    <div class="flex items-center text-sm text-gray-500">
                      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                      </svg>
                      {{ formatDate(registration.event.date) }}
                    </div>
                    <div class="flex items-center text-sm text-gray-500">
                      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                      </svg>
                      {{ registration.event.venue }}
                    </div>
                    <div class="flex items-center text-sm text-gray-500">
                      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                      </svg>
                      ${{ registration.event.price }}
                    </div>
                  </div>
                </div>
                
                <div class="flex items-center space-x-2 ml-4">
                  <span
                    :class="[
                      'px-2 py-1 text-xs font-medium rounded-full',
                      registration.status === 'registered'
                        ? 'bg-green-100 text-green-800'
                        : registration.status === 'cancelled'
                        ? 'bg-red-100 text-red-800'
                        : 'bg-yellow-100 text-yellow-800'
                    ]"
                  >
                    {{ registration.status }}
                  </span>
                  
                  <NuxtLink
                    :to="`/events/${registration.event.id}`"
                    class="text-blue-600 hover:text-blue-800 text-sm font-medium"
                  >
                    View Details
                  </NuxtLink>
                  
                  <button
                    v-if="showCancel && registration.status === 'registered' && isUpcoming(registration.event.date)"
                    @click="$emit('cancel', registration.id)"
                    class="text-red-600 hover:text-red-800 text-sm font-medium"
                  >
                    Cancel
                  </button>
                </div>
              </div>
              
              <div v-if="registration.event.description" class="mt-3 text-sm text-gray-600 line-clamp-2">
                {{ registration.event.description }}
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div v-else class="text-center py-12">
        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        <p class="text-gray-600">{{ emptyMessage }}</p>
        <NuxtLink 
          to="/" 
          class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-600 bg-blue-100 hover:bg-blue-200 transition-colors"
        >
          Explore Events
        </NuxtLink>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

interface Event {
  id: number
  title: string
  description?: string
  venue: string
  date: string
  price: number
  organization?: {
    name: string
  }
}

interface Registration {
  id: number
  status: string
  event: Event
}

interface Props {
  events: Registration[]
  title: string
  emptyMessage: string
  showCancel?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  showCancel: true
})

defineEmits<{
  cancel: [registrationId: number]
}>()

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const isUpcoming = (dateString: string) => {
  return new Date(dateString) > new Date()
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style> 