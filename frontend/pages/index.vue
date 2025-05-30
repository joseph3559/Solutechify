<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-6">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">Event Management System</h1>
            <p class="text-gray-600 mt-1">Discover events from various organizations</p>
          </div>
          <div class="flex space-x-4">
            <NuxtLink to="/admin/login" class="text-blue-600 hover:text-blue-800">
              Admin Login
                  </NuxtLink>
                </div>
              </div>
            </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Loading State -->
      <div v-if="pending" class="flex justify-center items-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="text-center py-12">
        <div class="text-red-600 text-lg">{{ error.message || 'Failed to load organizations' }}</div>
      </div>

      <!-- Organizations List -->
      <div v-else>
        <div class="mb-8">
          <h2 class="text-2xl font-bold text-gray-900 mb-4">Organizations</h2>
          <p class="text-gray-600">Browse events by organization</p>
        </div>

        <!-- No Organizations -->
        <div v-if="!organizations || organizations.length === 0" class="text-center py-12">
          <div class="text-gray-500 text-lg">No organizations available at the moment.</div>
          <p class="text-gray-400 mt-2">Check back soon!</p>
        </div>

        <!-- Organizations Grid -->
        <div v-else class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
          <NuxtLink
            v-for="organization in organizations"
            :key="organization.id"
            :to="`/${organization.slug}`"
            class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow block"
          >
            <div class="p-6">
              <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ organization.name }}</h3>
              <p class="text-gray-600 mb-4">{{ organization.description || 'Discover their upcoming events' }}</p>
              
              <div class="flex items-center justify-between">
                <span class="text-sm text-gray-500">
                  {{ organization.events_count || 0 }} events
                </span>
                <span class="text-blue-600 text-sm font-medium">
                  View Events →
              </span>
            </div>
            </div>
              </NuxtLink>
        </div>
      </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="text-center text-gray-500">
          <p>&copy; 2025 Event Management System. Built with Laravel & Nuxt.js</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup lang="ts">
const config = useRuntimeConfig()

// Fetch organizations
const { data: organizations, pending, error } = await useFetch('/organizations', {
  baseURL: config.public.apiBase,
  server: false,
  transform: (data: any) => {
    // Add events count if available
    return Array.isArray(data) ? data : []
  }
})

// SEO
useHead({
  title: 'Event Management System',
  meta: [
    { name: 'description', content: 'Discover and register for events from various organizations' }
  ]
})
</script>