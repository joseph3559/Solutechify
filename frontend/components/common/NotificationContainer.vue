<template>
  <Teleport to="body">
    <div class="fixed top-4 right-4 z-50 space-y-2 max-w-sm">
      <TransitionGroup
        name="notification"
        tag="div"
        class="space-y-2"
      >
        <div
          v-for="notification in notifications"
          :key="notification.id"
          :class="[
            'rounded-lg shadow-lg p-4 flex items-start space-x-3 border',
            {
              'bg-green-50 border-green-200': notification.type === 'success',
              'bg-red-50 border-red-200': notification.type === 'error',
              'bg-yellow-50 border-yellow-200': notification.type === 'warning',
              'bg-blue-50 border-blue-200': notification.type === 'info'
            }
          ]"
        >
          <!-- Icon -->
          <div class="flex-shrink-0">
            <!-- Success Icon -->
            <svg
              v-if="notification.type === 'success'"
              class="w-5 h-5 text-green-600"
              fill="currentColor"
              viewBox="0 0 20 20"
            >
              <path
                fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                clip-rule="evenodd"
              />
            </svg>
            
            <!-- Error Icon -->
            <svg
              v-else-if="notification.type === 'error'"
              class="w-5 h-5 text-red-600"
              fill="currentColor"
              viewBox="0 0 20 20"
            >
              <path
                fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                clip-rule="evenodd"
              />
            </svg>
            
            <!-- Warning Icon -->
            <svg
              v-else-if="notification.type === 'warning'"
              class="w-5 h-5 text-yellow-600"
              fill="currentColor"
              viewBox="0 0 20 20"
            >
              <path
                fill-rule="evenodd"
                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                clip-rule="evenodd"
              />
            </svg>
            
            <!-- Info Icon -->
            <svg
              v-else-if="notification.type === 'info'"
              class="w-5 h-5 text-blue-600"
              fill="currentColor"
              viewBox="0 0 20 20"
            >
              <path
                fill-rule="evenodd"
                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                clip-rule="evenodd"
              />
            </svg>
          </div>

          <!-- Content -->
          <div class="flex-1 min-w-0">
            <p
              :class="[
                'text-sm font-medium',
                {
                  'text-green-800': notification.type === 'success',
                  'text-red-800': notification.type === 'error',
                  'text-yellow-800': notification.type === 'warning',
                  'text-blue-800': notification.type === 'info'
                }
              ]"
            >
              {{ notification.title }}
            </p>
            <p
              v-if="notification.message"
              :class="[
                'mt-1 text-sm',
                {
                  'text-green-700': notification.type === 'success',
                  'text-red-700': notification.type === 'error',
                  'text-yellow-700': notification.type === 'warning',
                  'text-blue-700': notification.type === 'info'
                }
              ]"
            >
              {{ notification.message }}
            </p>
          </div>

          <!-- Close Button -->
          <div class="flex-shrink-0">
            <button
              @click="remove(notification.id)"
              :class="[
                'rounded-md p-1.5 focus:outline-none focus:ring-2 focus:ring-offset-2',
                {
                  'text-green-500 hover:bg-green-100 focus:ring-green-600': notification.type === 'success',
                  'text-red-500 hover:bg-red-100 focus:ring-red-600': notification.type === 'error',
                  'text-yellow-500 hover:bg-yellow-100 focus:ring-yellow-600': notification.type === 'warning',
                  'text-blue-500 hover:bg-blue-100 focus:ring-blue-600': notification.type === 'info'
                }
              ]"
            >
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path
                  fill-rule="evenodd"
                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                  clip-rule="evenodd"
                />
              </svg>
            </button>
          </div>
        </div>
      </TransitionGroup>
    </div>
  </Teleport>
</template>

<script setup lang="ts">
import { useNotification } from '~/composables/useNotification'

const { notifications, remove } = useNotification()
</script>

<style scoped>
.notification-enter-active,
.notification-leave-active {
  transition: all 0.3s ease;
}

.notification-enter-from {
  opacity: 0;
  transform: translateX(100%);
}

.notification-leave-to {
  opacity: 0;
  transform: translateX(100%);
}

.notification-move {
  transition: transform 0.3s ease;
}
</style> 