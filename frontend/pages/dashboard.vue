<template>
  <div class="min-h-screen bg-gray-100">
    <div class="py-10">
      <header>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h1 class="text-3xl font-bold leading-tight text-gray-900">Organizations</h1>
        </div>
      </header>
      <main>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <!-- Create Organization Form -->
          <div class="bg-white shadow sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
              <h3 class="text-lg leading-6 font-medium text-gray-900">Create New Organization</h3>
              <div class="mt-5">
                <form @submit.prevent="createNewOrganization" class="space-y-4">
                  <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input
                      type="text"
                      id="name"
                      v-model="newOrg.name"
                      required
                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    />
                  </div>
                  <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea
                      id="description"
                      v-model="newOrg.description"
                      rows="3"
                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    ></textarea>
                  </div>
                  <button
                    type="submit"
                    :disabled="organizationStore.loading"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                  >
                    {{ organizationStore.loading ? 'Creating...' : 'Create Organization' }}
                  </button>
                </form>
              </div>
            </div>
          </div>

          <!-- Organizations List -->
          <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul role="list" class="divide-y divide-gray-200">
              <li v-for="org in organizationStore.organizations" :key="org.id">
                <div class="px-4 py-4 sm:px-6">
                  <div class="flex items-center justify-between">
                    <div>
                      <h3 class="text-lg font-medium text-gray-900">{{ org.name }}</h3>
                      <p class="mt-1 text-sm text-gray-500">{{ org.description }}</p>
                    </div>
                    <div>
                      <NuxtLink
                        :to="`/organizations/${org.slug}/events`"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200"
                      >
                        Manage Events
                      </NuxtLink>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useAuthStore } from '~/stores/auth'
import { useOrganizationStore } from '~/stores/organization'
import { useRouter } from 'vue-router'

const router = useRouter()
const authStore = useAuthStore()
const organizationStore = useOrganizationStore()

const newOrg = ref({
  name: '',
  description: ''
})

const createNewOrganization = async () => {
  try {
    await organizationStore.createOrganization(newOrg.value)
    newOrg.value = { name: '', description: '' }
  } catch (error) {
    console.error('Failed to create organization:', error)
  }
}

const logout = () => {
  authStore.logout()
  router.push('/login')
}

onMounted(async () => {
  if (!authStore.token) {
    router.push('/login')
    return
  }
  await organizationStore.fetchOrganizations()
})
</script> 