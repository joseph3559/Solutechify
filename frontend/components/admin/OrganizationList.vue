<template>
  <div class="bg-white rounded-xl shadow-sm">
    <div class="p-6 border-b border-gray-200">
      <h2 class="text-xl font-semibold text-gray-900">Organizations Management</h2>
    </div>
    <div class="p-6">
      <div v-if="organizations?.length" class="space-y-4">
        <div v-for="org in organizations" :key="org.id" class="border border-gray-200 rounded-lg p-4">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-lg font-medium text-gray-900">{{ org.name }}</h3>
              <p class="text-sm text-gray-600">{{ org.events_count }} events</p>
            </div>
            <div class="flex space-x-2">
              <button
                @click="$emit('edit', org)"
                class="text-blue-600 hover:text-blue-900 text-sm font-medium"
              >
                Edit
              </button>
              <button
                @click="$emit('delete', org.id)"
                class="text-red-600 hover:text-red-900 text-sm font-medium"
              >
                Delete
              </button>
            </div>
          </div>
        </div>
      </div>
      <div v-else class="text-center py-12">
        <p class="text-gray-600">No organizations found.</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
interface Organization {
  id: number
  name: string
  events_count: number
}

interface Props {
  organizations: Organization[]
}

defineProps<Props>()

defineEmits<{
  edit: [organization: Organization]
  delete: [orgId: number]
  refresh: []
}>()
</script> 