<template>
  <nav class="bg-white dark:bg-gray-800 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex">
          <div class="flex-shrink-0 flex items-center">
            <NuxtLink to="/" class="flex items-center space-x-2">
              <span class="text-2xl font-bold text-primary dark:text-blue-400">Solutechify</span>
            </NuxtLink>
          </div>
          <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
            <template v-if="!authStore.isAuthenticated">
              <a 
                v-for="item in navigationItems" 
                :key="item.name"
                :href="item.href"
                class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white"
                @click.prevent="scrollToSection(item.href)"
              >
                {{ item.name }}
              </a>
            </template>
            <template v-else>
              <NuxtLink 
                to="/dashboard" 
                class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white"
                :class="{ 'border-b-2 border-primary dark:border-blue-400': route.path === '/dashboard' }"
              >
                Dashboard
              </NuxtLink>
              <NuxtLink 
                to="/organizations" 
                class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white"
                :class="{ 'border-b-2 border-primary dark:border-blue-400': route.path.startsWith('/organizations') }"
              >
                Organizations
              </NuxtLink>
            </template>
            <NuxtLink 
              to="/events" 
              class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white"
              :class="{ 'border-b-2 border-primary dark:border-blue-400': route.path.startsWith('/events') }"
            >
              Events
            </NuxtLink>
          </div>
        </div>
        <div class="hidden sm:ml-6 sm:flex sm:items-center sm:space-x-4">
          <CommonThemeToggle />
          <template v-if="!authStore.isAuthenticated">
            <NuxtLink to="/login">
              <CommonButton variant="secondary">Login</CommonButton>
            </NuxtLink>
            <NuxtLink to="/register">
              <CommonButton>Get Started</CommonButton>
            </NuxtLink>
          </template>
          <template v-else>
            <span class="text-sm text-gray-700 dark:text-gray-300">{{ authStore.user?.name }}</span>
            <CommonButton variant="secondary" @click="handleLogout">Logout</CommonButton>
          </template>
        </div>
        <!-- Mobile menu button -->
        <div class="flex items-center space-x-2 sm:hidden">
          <CommonThemeToggle />
          <button
            type="button"
            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
            @click="isOpen = !isOpen"
          >
            <span class="sr-only">Open main menu</span>
            <svg
              class="h-6 w-6"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                v-if="!isOpen"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"
              />
              <path
                v-else
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile menu -->
    <div v-show="isOpen" class="sm:hidden">
      <div class="pt-2 pb-3 space-y-1">
        <template v-if="!authStore.isAuthenticated">
          <a
            v-for="item in navigationItems"
            :key="item.name"
            :href="item.href"
            class="block pl-3 pr-4 py-2 text-base font-medium text-gray-500 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700"
            @click.prevent="scrollToSection(item.href); isOpen = false"
          >
            {{ item.name }}
          </a>
        </template>
        <template v-else>
          <NuxtLink
            to="/dashboard"
            class="block pl-3 pr-4 py-2 text-base font-medium text-gray-500 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700"
            :class="{ 'bg-gray-50 dark:bg-gray-700': route.path === '/dashboard' }"
            @click="isOpen = false"
          >
            Dashboard
          </NuxtLink>
          <NuxtLink
            to="/organizations"
            class="block pl-3 pr-4 py-2 text-base font-medium text-gray-500 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700"
            :class="{ 'bg-gray-50 dark:bg-gray-700': route.path.startsWith('/organizations') }"
            @click="isOpen = false"
          >
            Organizations
          </NuxtLink>
        </template>
        <NuxtLink
          to="/events"
          class="block pl-3 pr-4 py-2 text-base font-medium text-gray-500 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700"
          :class="{ 'bg-gray-50 dark:bg-gray-700': route.path.startsWith('/events') }"
          @click="isOpen = false"
        >
          Events
        </NuxtLink>
      </div>
      <div class="pt-4 pb-3 border-t border-gray-200 dark:border-gray-700">
        <div class="space-y-2 px-4">
          <template v-if="!authStore.isAuthenticated">
            <NuxtLink
              to="/login"
              class="block text-center w-full rounded-md py-2 text-sm font-medium text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700 border border-gray-300 dark:border-gray-600"
              @click="isOpen = false"
            >
              Login
            </NuxtLink>
            <NuxtLink
              to="/register"
              class="block text-center w-full rounded-md py-2 text-sm font-medium text-white bg-primary hover:bg-primary/90 dark:bg-blue-600 dark:hover:bg-blue-700"
              @click="isOpen = false"
            >
              Get Started
            </NuxtLink>
          </template>
          <template v-else>
            <div class="text-sm text-gray-700 dark:text-gray-300 text-center mb-2">{{ authStore.user?.name }}</div>
            <button
              @click="handleLogout"
              class="block text-center w-full rounded-md py-2 text-sm font-medium text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700 border border-gray-300 dark:border-gray-600"
            >
              Logout
            </button>
          </template>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup lang="ts">
import { ref, onMounted, nextTick } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '~/stores/auth'

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()
const isOpen = ref(false)

const navigationItems = [
  { name: 'Features', href: '#features' },
  { name: 'Pricing', href: '#pricing' },
  { name: 'Contact', href: '#contact' },
]

const scrollToSection = async (hash: string) => {
  // If not on home page, navigate to home page first
  if (route.path !== '/') {
    await router.push('/')
    // Wait for navigation to complete
    await nextTick()
  }
  
  const element = document.querySelector(hash)
  if (element) {
    element.scrollIntoView({
      behavior: 'smooth',
      block: 'start'
    })
  }
}

const handleLogout = () => {
  authStore.logout()
  router.push('/')
  isOpen.value = false
}

// Handle initial hash navigation
onMounted(() => {
  if (route.hash) {
    // Add a small delay to ensure the page is fully loaded
    setTimeout(() => {
      scrollToSection(route.hash)
    }, 100)
  }
})
</script>
