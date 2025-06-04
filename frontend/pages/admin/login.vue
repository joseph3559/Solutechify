<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full text-center space-y-8">
      <div class="bg-white rounded-2xl shadow-xl p-8">
        <div class="mb-6">
          <NuxtLink to="/" class="inline-flex items-center space-x-2 text-blue-600 hover:text-blue-800 transition-colors">
            <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2L2 7v10c0 5.55 3.84 9.99 9 11 5.16-1.01 9-5.45 9-11V7l-10-5z"/>
            </svg>
            <span class="text-2xl font-bold">Solutechify Events</span>
          </NuxtLink>
        </div>
        
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Admin Access</h2>
        <p class="text-gray-600 mb-6">Please use our unified sign-in page to access your admin dashboard.</p>
        
        <NuxtLink 
          to="/auth"
          class="inline-flex items-center justify-center w-full py-3 px-4 border border-transparent rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200"
        >
          Go to Sign In Page
        </NuxtLink>
        
        <div class="mt-6 text-sm text-gray-500">
          <NuxtLink to="/" class="text-blue-600 hover:text-blue-800">
            ← Back to Home
          </NuxtLink>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
// Automatically redirect to the unified auth page
await navigateTo('/auth')

// SEO
useHead({
  title: 'Admin Login - SolutechifyEvents',
  meta: [
    { name: 'description', content: 'Admin login redirect to unified authentication page' }
  ]
})
</script> 