<template>
  <div class="calendar">
    <!-- Calendar Header -->
    <div class="grid grid-cols-7 gap-1 mb-2">
      <div
        v-for="day in daysOfWeek"
        :key="day"
        class="p-2 text-center text-sm font-medium text-gray-500"
      >
        {{ day }}
      </div>
    </div>

    <!-- Calendar Days -->
    <div class="grid grid-cols-7 gap-1">
      <div
        v-for="day in calendarDays"
        :key="`${day.date}-${day.month}`"
        :class="[
          'min-h-20 p-1 border border-gray-200 rounded',
          day.isCurrentMonth ? 'bg-white' : 'bg-gray-50',
          day.isToday ? 'bg-blue-50 border-blue-200' : ''
        ]"
      >
        <div
          :class="[
            'text-sm font-medium mb-1',
            day.isCurrentMonth ? 'text-gray-900' : 'text-gray-400',
            day.isToday ? 'text-blue-600' : ''
          ]"
        >
          {{ day.date }}
        </div>
        
        <div class="space-y-1">
          <div
            v-for="event in day.events"
            :key="event.id"
            class="text-xs bg-blue-100 text-blue-800 px-1 py-0.5 rounded truncate cursor-pointer hover:bg-blue-200 transition-colors"
            :title="`${event.title} at ${event.venue}`"
            @click="showEventDetails(event)"
          >
            {{ event.title }}
          </div>
        </div>
      </div>
    </div>

    <!-- Event Details Modal -->
    <div
      v-if="selectedEvent"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
      @click="selectedEvent = null"
    >
      <div
        class="bg-white rounded-lg p-6 max-w-md w-full mx-4"
        @click.stop
      >
        <div class="flex items-start justify-between mb-4">
          <h3 class="text-lg font-medium text-gray-900">{{ selectedEvent.title }}</h3>
          <button
            @click="selectedEvent = null"
            class="text-gray-400 hover:text-gray-600"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
        
        <div class="space-y-3">
          <div class="flex items-center text-sm text-gray-600">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            {{ formatEventDate(selectedEvent.date) }}
          </div>
          
          <div class="flex items-center text-sm text-gray-600">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            {{ selectedEvent.venue }}
          </div>
          
          <div class="flex items-center text-sm text-gray-600">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H9m0 0H5m4 0v-2a1 1 0 011-1h1a1 1 0 011 1v2m-4 0V9a1 1 0 011-1h1a1 1 0 011 1v12"></path>
            </svg>
            {{ selectedEvent.organization }}
          </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
          <button
            @click="selectedEvent = null"
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition-colors"
          >
            Close
          </button>
          <NuxtLink
            :to="`/events/${selectedEvent.id}`"
            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md transition-colors"
          >
            View Details
          </NuxtLink>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'

interface CalendarEvent {
  id: number
  title: string
  date: string
  venue: string
  organization: string
  registration_id: number
  status: string
}

interface Props {
  events: CalendarEvent[]
  currentMonth: number
  currentYear: number
}

const props = defineProps<Props>()

const selectedEvent = ref<CalendarEvent | null>(null)

const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']

const calendarDays = computed(() => {
  const firstDay = new Date(props.currentYear, props.currentMonth - 1, 1)
  const lastDay = new Date(props.currentYear, props.currentMonth, 0)
  const startDate = new Date(firstDay)
  startDate.setDate(startDate.getDate() - firstDay.getDay())
  
  const days = []
  const today = new Date()
  
  for (let i = 0; i < 42; i++) { // 6 weeks * 7 days
    const currentDate = new Date(startDate)
    currentDate.setDate(startDate.getDate() + i)
    
    const dayEvents = props.events.filter(event => {
      const eventDate = new Date(event.date)
      return eventDate.getDate() === currentDate.getDate() &&
             eventDate.getMonth() === currentDate.getMonth() &&
             eventDate.getFullYear() === currentDate.getFullYear()
    })
    
    days.push({
      date: currentDate.getDate(),
      month: currentDate.getMonth(),
      year: currentDate.getFullYear(),
      isCurrentMonth: currentDate.getMonth() === props.currentMonth - 1,
      isToday: currentDate.toDateString() === today.toDateString(),
      events: dayEvents
    })
  }
  
  return days
})

const showEventDetails = (event: CalendarEvent) => {
  selectedEvent.value = event
}

const formatEventDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}
</script>

<style scoped>
.calendar {
  @apply w-full;
}
</style> 