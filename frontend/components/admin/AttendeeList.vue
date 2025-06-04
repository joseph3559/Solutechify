<template>
  <div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-200">
      <h2 class="text-xl font-semibold text-gray-900">Attendees Management</h2>
    </div>
    <div class="p-6">
      <div v-if="attendees?.length" class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registered</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="attendee in attendees" :key="attendee.id">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ attendee.name }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ attendee.email }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ attendee.event?.title }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="getStatusClass(attendee.status)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                  {{ attendee.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ formatDate(attendee.created_at) }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-else class="text-center py-12">
        <p class="text-gray-600">No attendees found.</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
interface Attendee {
  id: number
  name: string
  email: string
  status: string
  created_at: string
  event?: {
    title: string
  }
}

interface Props {
  attendees: Attendee[]
}

defineProps<Props>()

defineEmits<{
  refresh: []
}>()

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const getStatusClass = (status: string) => {
  switch (status) {
    case 'registered':
      return 'bg-green-100 text-green-800'
    case 'cancelled':
      return 'bg-red-100 text-red-800'
    default:
      return 'bg-gray-100 text-gray-800'
  }
}
</script> 