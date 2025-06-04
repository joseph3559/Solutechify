<template>
  <div class="min-h-screen bg-white dark:bg-slate-900 transition-colors duration-300">
    <header class="fixed top-0 left-0 right-0 z-50 bg-white/80 dark:bg-slate-900/80 backdrop-blur-lg border-b border-slate-200/50 dark:border-slate-800/50">
      <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <!-- Left side - Logo and main nav -->
          <div class="flex items-center">
            <NuxtLink to="/" class="flex items-center flex-shrink-0">
              <!-- Logo -->
              <div class="w-8 h-8 rounded bg-blue-600 flex items-center justify-center mr-2">
                <span class="text-lg font-bold text-white">S</span>
              </div>
              <span class="text-xl font-bold text-gray-900 dark:text-white">Solutechify</span>
            </NuxtLink>

            <!-- Desktop Navigation -->
            <div class="hidden md:ml-10 md:flex md:items-center md:space-x-4">
              <NuxtLink 
                to="/events" 
                class="text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200"
              >
                Events
              </NuxtLink>
              <div class="relative" @mouseenter="showFeaturesMenu = true" @mouseleave="showFeaturesMenu = false">
                <button 
                  class="text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 flex items-center"
                >
                  Features
                  <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </button>
                <!-- Features Dropdown -->
                <Transition
                  enter-active-class="transition duration-100 ease-out"
                  enter-from-class="transform scale-95 opacity-0"
                  enter-to-class="transform scale-100 opacity-100"
                  leave-active-class="transition duration-75 ease-in"
                  leave-from-class="transform scale-100 opacity-100"
                  leave-to-class="transform scale-95 opacity-0"
                >
                  <div v-if="showFeaturesMenu" class="absolute left-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white dark:bg-slate-800 ring-1 ring-black ring-opacity-5">
                    <NuxtLink 
                      v-for="(feature, index) in features" 
                      :key="index"
                      :to="feature.path"
                      class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors duration-200"
                    >
                      {{ feature.name }}
                    </NuxtLink>
                  </div>
                </Transition>
              </div>
              <NuxtLink 
                to="/pricing" 
                class="text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200"
              >
                Pricing
              </NuxtLink>
              <NuxtLink 
                to="/contact" 
                class="text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200"
              >
                Contact
              </NuxtLink>
            </div>
          </div>

          <!-- Right side - Theme toggle and auth buttons -->
          <div class="flex items-center space-x-4">
            <!-- Theme Toggle -->
            <button
              @click="toggleColorMode"
              class="relative inline-flex h-10 w-10 items-center justify-center rounded-lg bg-white/10 hover:bg-white/20 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500/40 dark:bg-slate-800/50 dark:hover:bg-slate-800/70"
              :aria-label="$colorMode.value === 'dark' ? 'Switch to light mode' : 'Switch to dark mode'"
            >
              <!-- Sun icon -->
              <Transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="opacity-0 scale-50"
                enter-to-class="opacity-100 scale-100"
                leave-active-class="transition duration-200 ease-in"
                leave-from-class="opacity-100 scale-100"
                leave-to-class="opacity-0 scale-50"
              >
                <svg
                  v-if="$colorMode.value === 'light'"
                  class="h-5 w-5 text-yellow-500"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"
                  />
                </svg>
              </Transition>

              <!-- Moon icon -->
              <Transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="opacity-0 scale-50"
                enter-to-class="opacity-100 scale-100"
                leave-active-class="transition duration-200 ease-in"
                leave-from-class="opacity-100 scale-100"
                leave-to-class="opacity-0 scale-50"
              >
                <svg
                  v-if="$colorMode.value === 'dark'"
                  class="h-5 w-5 text-blue-400"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"
                  />
                </svg>
              </Transition>
            </button>

            <!-- Desktop Auth Buttons -->
            <div class="hidden md:flex items-center space-x-4">
              <template v-if="!authInitialized">
                <!-- Loading state while auth initializes -->
                <div class="flex items-center space-x-4">
                  <div class="w-16 h-8 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
                  <div class="w-20 h-8 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
                </div>
              </template>
              <template v-else-if="!isAuthenticated">
                <NuxtLink 
                  to="/auth"
                  class="text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200"
                >
                  Sign In
                </NuxtLink>
                <NuxtLink 
                  to="/auth"
                  class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200"
                >
                  Get Started
                </NuxtLink>
              </template>
              <template v-else>
                <div class="text-sm text-gray-600 dark:text-gray-300">
                  Welcome, <span class="font-medium text-gray-900 dark:text-white">{{ currentUser?.name }}</span>
                  <span class="ml-2 px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                    {{ currentUser?.role }}
                  </span>
                </div>
                <NuxtLink 
                  :to="isAdmin ? '/admin/dashboard' : '/user/dashboard'"
                  class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200"
                >
                  Dashboard
                </NuxtLink>
                <NuxtLink 
                  to="/events"
                  class="text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200"
                >
                  Browse Events
                </NuxtLink>
                <div class="relative" @mouseenter="showUserMenu = true" @mouseleave="showUserMenu = false">
                  <button 
                    class="flex items-center text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200"
                  >
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Profile
                    <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                  </button>
                  <!-- User Dropdown -->
                  <Transition
                    enter-active-class="transition duration-100 ease-out"
                    enter-from-class="transform scale-95 opacity-0"
                    enter-to-class="transform scale-100 opacity-100"
                    leave-active-class="transition duration-75 ease-in"
                    leave-from-class="transform scale-100 opacity-100"
                    leave-to-class="transform scale-95 opacity-0"
                  >
                    <div v-if="showUserMenu" class="absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white dark:bg-slate-800 ring-1 ring-black ring-opacity-5">
                      <div class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400 border-b dark:border-slate-700">
                        {{ currentUser?.email }}
                      </div>
                      <button 
                        @click="handleLogout"
                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-700"
                      >
                        Sign Out
                      </button>
                    </div>
                  </Transition>
                </div>
              </template>
            </div>

            <!-- Mobile menu button -->
            <button
              @click="isMobileMenuOpen = !isMobileMenuOpen"
              class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500"
            >
              <span class="sr-only">Open main menu</span>
              <svg
                :class="{'hidden': isMobileMenuOpen, 'block': !isMobileMenuOpen}"
                class="h-6 w-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              </svg>
              <svg
                :class="{'block': isMobileMenuOpen, 'hidden': !isMobileMenuOpen}"
                class="h-6 w-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>

        <!-- Mobile menu -->
        <Transition
          enter-active-class="transition duration-200 ease-out"
          enter-from-class="transform -translate-y-4 opacity-0"
          enter-to-class="transform translate-y-0 opacity-100"
          leave-active-class="transition duration-150 ease-in"
          leave-from-class="transform translate-y-0 opacity-100"
          leave-to-class="transform -translate-y-4 opacity-0"
        >
          <div v-if="isMobileMenuOpen" class="md:hidden py-2">
            <div class="px-2 pt-2 pb-3 space-y-1">
              <NuxtLink 
                to="/events"
                class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-slate-800"
              >
                Events
              </NuxtLink>
              <div>
                <button 
                  @click="showMobileFeaturesMenu = !showMobileFeaturesMenu"
                  class="w-full flex items-center justify-between px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-slate-800"
                >
                  Features
                  <svg 
                    class="h-4 w-4 transform transition-transform duration-200"
                    :class="{'rotate-180': showMobileFeaturesMenu}"
                    fill="none" 
                    stroke="currentColor" 
                    viewBox="0 0 24 24"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </button>
                <Transition
                  enter-active-class="transition duration-100 ease-out"
                  enter-from-class="transform scale-95 opacity-0"
                  enter-to-class="transform scale-100 opacity-100"
                  leave-active-class="transition duration-75 ease-in"
                  leave-from-class="transform scale-100 opacity-100"
                  leave-to-class="transform scale-95 opacity-0"
                >
                  <div v-if="showMobileFeaturesMenu" class="pl-4">
                    <NuxtLink 
                      v-for="(feature, index) in features" 
                      :key="index"
                      :to="feature.path"
                      class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-slate-800"
                    >
                      {{ feature.name }}
                    </NuxtLink>
                  </div>
                </Transition>
              </div>
              <NuxtLink 
                to="/pricing"
                class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-slate-800"
              >
                Pricing
              </NuxtLink>
              <NuxtLink 
                to="/contact"
                class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-slate-800"
              >
                Contact
              </NuxtLink>
              <div class="pt-4 pb-3 border-t border-gray-200 dark:border-slate-700">
                <template v-if="!authInitialized">
                  <!-- Loading state while auth initializes -->
                  <div class="px-3 py-2">
                    <div class="w-full h-8 bg-gray-200 dark:bg-gray-700 rounded animate-pulse mb-2"></div>
                    <div class="w-full h-8 bg-gray-200 dark:bg-gray-700 rounded animate-pulse"></div>
                  </div>
                </template>
                <template v-else-if="!isAuthenticated">
                  <NuxtLink 
                    to="/auth"
                    class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-slate-800"
                  >
                    Sign In
                  </NuxtLink>
                  <NuxtLink 
                    to="/auth"
                    class="block px-3 py-2 rounded-md text-base font-medium bg-blue-600 text-white hover:bg-blue-700 mt-2"
                  >
                    Get Started
                  </NuxtLink>
                </template>
                <template v-else>
                  <div class="px-3 py-2 border-b border-gray-200 dark:border-slate-700 mb-2">
                    <div class="text-base font-medium text-gray-900 dark:text-white">{{ currentUser?.name }}</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">{{ currentUser?.email }}</div>
                    <div class="mt-1">
                      <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                        {{ currentUser?.role }}
                      </span>
                    </div>
                  </div>
                  <NuxtLink 
                    :to="isAdmin ? '/admin/dashboard' : '/user/dashboard'"
                    class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-slate-800"
                  >
                    Dashboard
                  </NuxtLink>
                  <NuxtLink 
                    to="/events"
                    class="block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-slate-800"
                  >
                    Browse Events
                  </NuxtLink>
                  <button 
                    @click="handleLogout"
                    class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-slate-800"
                  >
                    Sign Out
                  </button>
                </template>
              </div>
            </div>
          </div>
        </Transition>
      </nav>
    </header>

    <!-- Add padding to account for fixed header -->
    <div class="pt-16">
      <slot />
    </div>

    <footer class="bg-slate-50 dark:bg-slate-800/50 border-t border-slate-200/50 dark:border-slate-800">
      <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="text-center text-sm text-gray-500 dark:text-gray-400">
          <p>&copy; {{ new Date().getFullYear() }} Solutechify. All rights reserved.</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '~/stores/auth'
import { useRouter } from 'vue-router'

const router = useRouter()
const authStore = useAuthStore()
const colorMode = useColorMode()

// Reactive state
const isMobileMenuOpen = ref(false)
const showFeaturesMenu = ref(false)
const showMobileFeaturesMenu = ref(false)
const showUserMenu = ref(false)
const authInitialized = ref(false)

// Computed properties
const isAuthenticated = computed(() => authStore.isAuthenticated)
const currentUser = computed(() => authStore.currentUser)
const isAdmin = computed(() => authStore.isAdmin)

const features = [
  { name: 'Event Management', path: '/features/event-management' },
  { name: 'Ticketing', path: '/features/ticketing' },
  { name: 'Analytics', path: '/features/analytics' },
  { name: 'Integrations', path: '/features/integrations' },
  { name: 'Security', path: '/features/security' },
]

const toggleColorMode = () => {
  colorMode.preference = colorMode.value === 'dark' ? 'light' : 'dark'
}

const handleLogout = async () => {
  try {
    await authStore.logout()
    router.push('/auth')
  } catch (error) {
    console.error('Logout error:', error)
  } finally {
    isMobileMenuOpen.value = false
    showUserMenu.value = false
  }
}

// Initialize authentication state
onMounted(async () => {
  await authStore.initAuth()
  authInitialized.value = true
})
</script>

<style>
/* Add smooth transitions for all color mode changes */
::view-transition-old(root),
::view-transition-new(root) {
  animation: none;
  mix-blend-mode: normal;
}

::view-transition-old(root) {
  z-index: 1;
}

::view-transition-new(root) {
  z-index: 2;
}

.dark::view-transition-old(root) {
  z-index: 2;
}

.dark::view-transition-new(root) {
  z-index: 1;
}

/* Add transition classes for color mode changes */
.transition-colors {
  transition-property: background-color, border-color, color, fill, stroke;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 300ms;
}
</style>
