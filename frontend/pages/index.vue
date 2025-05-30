<template>
  <div class="bg-white dark:bg-slate-900">
    <section class="relative overflow-hidden bg-white dark:bg-slate-900">
      <div class="max-w-7xl mx-auto">
        <div class="relative z-10 pb-8 sm:pb-16 md:pb-20 lg:pb-28 xl:pb-32 pt-6 md:pt-10">
          <div aria-hidden="true" class="absolute inset-0 overflow-hidden z-0">
            <div class="absolute top-0 right-0 -mt-20 -mr-20 md:-mt-28 md:-mr-28 w-96 h-96 bg-gradient-to-tr from-blue-100/80 via-indigo-50/70 to-purple-100/80 dark:from-blue-900/50 dark:via-indigo-900/30 dark:to-purple-900/30 rounded-full opacity-60 blur-3xl animate-float"></div>
            <div class="absolute bottom-0 left-0 -mb-20 -ml-20 md:-mb-28 md:-ml-28 w-80 h-80 bg-gradient-to-br from-pink-100/70 via-red-50/60 to-orange-100/70 dark:from-pink-900/50 dark:via-red-900/30 dark:to-orange-900/30 rounded-full opacity-50 blur-3xl animate-float-delayed"></div>
            <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>
          </div>

          <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28 relative z-10">
            <div class="sm:text-center lg:text-left">
              <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white sm:text-5xl md:text-6xl animate-fade-in">
                <span class="block xl:inline">Event Management,</span>
                <span class="block text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-400 dark:to-indigo-400 xl:inline animate-gradient"> Simplified</span>
              </h1>
              <p class="mt-3 text-base text-gray-600 dark:text-slate-300 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0 animate-fade-in-delayed">
                The smart event management platform built for modern organizations. Create, manage, and track your events with ease and professionalism.
              </p>
              <div class="mt-8 sm:mt-10 sm:flex sm:justify-center lg:justify-start animate-fade-in-delayed-2">
                <div class="rounded-xl shadow-xl hover:shadow-2xl transition-all duration-300">
                  <NuxtLink to="/register">
                    <CommonButton size="lg" class="w-full px-8 py-3 text-base font-medium bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 transform hover:scale-105 transition-all duration-300">Get Started</CommonButton>
                  </NuxtLink>
                </div>
                <div class="mt-3 sm:mt-0 sm:ml-4">
                  <NuxtLink to="/admin/login">
                    <CommonButton size="lg" variant="secondary" class="w-full px-8 py-3 text-base font-medium backdrop-blur-sm bg-white/10 dark:bg-slate-800/50 hover:bg-white/20 dark:hover:bg-slate-800/70 transform hover:scale-105 transition-all duration-300">Admin Login</CommonButton>
                  </NuxtLink>
                </div>
              </div>
            </div>
          </main>
        </div>
      </div>
    </section>

    <section id="features" class="py-16 md:py-24 bg-gradient-to-b from-slate-50 to-white dark:from-slate-800 dark:to-slate-900 relative">
      <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="lg:text-center">
          <h2 class="text-base text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-400 dark:to-indigo-400 font-semibold tracking-wide uppercase animate-fade-in">Features</h2>
          <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 dark:text-white sm:text-4xl animate-fade-in">
            Everything you need to manage events
          </p>
          <p class="mt-4 max-w-2xl text-xl text-gray-600 dark:text-slate-300 lg:mx-auto animate-fade-in-delayed">
            Powerful tools designed to streamline your event planning and execution, making management feel effortless.
          </p>
        </div>

        <div class="mt-12 md:mt-16">
          <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
            <div v-for="(feature, index) in features" :key="feature.name"
                 :class="['feature-card', `animate-fade-in-up-${index}`]"
                 class="group backdrop-blur-sm bg-white/40 dark:bg-slate-800/40 p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 ease-out transform hover:-translate-y-2 border border-gray-100/20 dark:border-slate-700/20">
              <div class="flex items-start space-x-6">
                <div class="flex-shrink-0">
                  <div class="flex items-center justify-center h-14 w-14 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-lg group-hover:shadow-xl group-hover:scale-110 transition-all duration-300">
                    <component :is="feature.icon" class="h-7 w-7 transform group-hover:rotate-12 transition-transform duration-300" aria-hidden="true" />
                  </div>
                </div>
                <div>
                  <h3 class="text-xl leading-6 font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-300">{{ feature.name }}</h3>
                  <p class="mt-3 text-base text-gray-600 dark:text-slate-300">{{ feature.description }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="py-16 md:py-24 bg-white dark:bg-slate-900 relative overflow-hidden">
      <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="lg:text-center">
          <h2 class="text-base text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-400 dark:to-indigo-400 font-semibold tracking-wide uppercase">How it Works</h2>
          <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
            Start managing events in 3 simple steps
          </p>
        </div>

        <div class="mt-12 md:mt-16">
          <div class="grid grid-cols-1 gap-y-12 gap-x-8 md:grid-cols-3">
            <div v-for="(step, index) in steps" :key="step.name"
                 :class="['step-card', `animate-fade-in-up-${index}`]"
                 class="relative flex flex-col items-center text-center p-8 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-all duration-500 group">
              <div class="flex items-center justify-center h-20 w-20 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-white text-2xl font-bold shadow-xl mb-8 ring-4 ring-blue-200 dark:ring-blue-500/30 group-hover:scale-110 transition-transform duration-300">
                {{ index + 1 }}
              </div>
              <h3 class="text-2xl font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-300">{{ step.name }}</h3>
              <p class="mt-4 text-lg text-gray-600 dark:text-slate-300">{{ step.description }}</p>
              <div v-if="index < 2" class="hidden md:block absolute top-24 right-0 w-full h-0.5 bg-gradient-to-r from-blue-500/20 to-indigo-600/20 transform translate-x-1/2"></div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="pricing" class="py-16 md:py-24 bg-gradient-to-b from-slate-50 to-white dark:from-slate-800 dark:to-slate-900 relative overflow-hidden">
      <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center">
          <h2 class="text-base text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-400 dark:to-indigo-400 font-semibold tracking-wide uppercase">Pricing</h2>
          <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
            Simple, transparent pricing
          </p>
          <p class="mt-4 max-w-2xl text-xl text-gray-600 dark:text-slate-300 mx-auto">
            Choose the plan that empowers your organization. No hidden fees, ever.
          </p>
        </div>

        <div class="mt-12 md:mt-16 grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3 items-stretch">
          <div v-for="(plan, index) in plans" :key="plan.name"
               :class="[
                 'pricing-card backdrop-blur-sm bg-white/40 dark:bg-slate-800/40 rounded-2xl p-8 flex flex-col transition-all duration-500 ease-out hover:transform hover:-translate-y-2',
                 index === 1 ? 'ring-2 ring-blue-500 dark:ring-blue-400 lg:scale-105 relative shadow-2xl pt-12' : 'ring-1 ring-gray-200 dark:ring-slate-700 shadow-xl hover:shadow-2xl',
                 `animate-fade-in-up-${index}`
               ]">
            <div v-if="index === 1" class="absolute -top-4 left-1/2 -translate-x-1/2">
              <span class="inline-flex items-center px-4 py-1 rounded-full text-sm font-bold tracking-wider uppercase bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-lg">
                Most Popular
              </span>
            </div>
            
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ plan.name }}</h3>
            <p class="mt-4 text-gray-600 dark:text-slate-300 flex-grow">{{ plan.description }}</p>
            <div class="mt-8 flex items-baseline">
              <span class="text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-400 dark:to-indigo-400">KSH {{ plan.price.toLocaleString() }}</span>
              <span class="ml-2 text-base text-gray-500 dark:text-slate-400">/month</span>
            </div>
            <ul role="list" class="mt-8 space-y-4">
              <li v-for="featureItem in plan.features" :key="featureItem" class="flex items-start">
                <CheckCircleIcon class="flex-shrink-0 h-6 w-6 text-green-500 dark:text-green-400" aria-hidden="true" />
                <span class="ml-3 text-gray-600 dark:text-slate-300">{{ featureItem }}</span>
              </li>
            </ul>
            <div class="mt-10">
              <NuxtLink :to="plan.price === 0 ? '/register' : '/register?plan=' + plan.name.toLowerCase()">
                <CommonButton 
                  :variant="index === 1 ? 'primary' : 'outline'" 
                  class="w-full py-3 transform hover:scale-105 transition-transform duration-300"
                  :class="index === 1 ? 'bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700' : ''">
                  {{ plan.price === 0 ? 'Get Started Free' : 'Choose Plan' }}
                </CommonButton>
              </NuxtLink>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="contact" class="py-16 md:py-24 bg-gradient-to-br from-blue-600 via-indigo-600 to-indigo-700 dark:from-blue-700 dark:via-indigo-700 dark:to-indigo-800 text-white relative overflow-hidden">
      <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
      <div class="absolute inset-0 bg-gradient-to-b from-transparent via-blue-600/10 to-indigo-900/20"></div>
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative">
        <h2 class="text-3xl font-extrabold sm:text-4xl animate-fade-in">Ready to Elevate Your Events?</h2>
        <p class="mt-4 text-lg text-blue-100 dark:text-blue-200 max-w-2xl mx-auto animate-fade-in-delayed">
          Join hundreds of organizations streamlining their event management with Solutechify. Get started today and experience the difference.
        </p>
        <div class="mt-10 animate-fade-in-delayed-2">
          <NuxtLink to="/register">
            <CommonButton 
              size="lg" 
              variant="secondary" 
              class="px-10 py-4 text-lg bg-white/10 backdrop-blur-sm hover:bg-white/20 transform hover:scale-105 transition-all duration-300 shadow-xl">
              Create Your Organization Now
            </CommonButton>
          </NuxtLink>
          <p class="mt-6 text-sm text-blue-200 dark:text-blue-300">
            Questions? <NuxtLink to="/contact" class="font-medium hover:underline">Contact our team</NuxtLink>.
          </p>
        </div>
      </div>
    </section>

    <!-- Organizations Section -->
    <section class="py-16 md:py-24 bg-white dark:bg-slate-900">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">Explore Organizations</h2>
          <p class="mt-4 text-lg text-gray-600 dark:text-slate-300">Browse events from our partner organizations</p>
        </div>
        
        <div v-if="pending" class="flex justify-center items-center py-12">
          <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
        </div>

        <div v-else-if="error" class="text-center py-12">
          <div class="bg-red-50 dark:bg-red-900/10 rounded-lg p-8 max-w-2xl mx-auto">
            <ExclamationCircleIcon class="h-12 w-12 text-red-500 mx-auto mb-4" />
            <h3 class="text-lg font-semibold text-red-700 dark:text-red-400 mb-2">Unable to Load Organizations</h3>
            <p class="text-red-600 dark:text-red-300 mb-4">
              {{ error.message || 'We are having trouble connecting to the server. This might be due to network issues or server maintenance.' }}
            </p>
            <div class="flex justify-center space-x-4">
              <CommonButton
                variant="outline"
                class="flex items-center space-x-2"
                @click="handleRefresh"
              >
                <ArrowPathIcon class="h-5 w-5" />
                <span>Try Again</span>
              </CommonButton>
              <NuxtLink to="/contact">
                <CommonButton variant="secondary" class="flex items-center space-x-2">
                  <ChatBubbleLeftIcon class="h-5 w-5" />
                  <span>Contact Support</span>
                </CommonButton>
              </NuxtLink>
            </div>
          </div>
        </div>

        <div v-else-if="!organizations || organizations.length === 0" class="text-center py-12">
          <div class="text-gray-500 text-lg">No organizations available at the moment.</div>
          <p class="text-gray-400 mt-2">Check back soon!</p>
        </div>

        <div v-else class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
          <NuxtLink
            v-for="organization in organizations"
            :key="organization.id"
            :to="`/${organization.slug}`"
            class="bg-white dark:bg-slate-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow block border border-gray-200 dark:border-slate-700"
          >
            <div class="p-6">
              <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">{{ organization.name }}</h3>
              <p class="text-gray-600 dark:text-slate-300 mb-4">{{ organization.description || 'Discover their upcoming events' }}</p>
              
              <div class="flex items-center justify-between">
                <span class="text-sm text-gray-500 dark:text-slate-400">
                  {{ organization.events_count || 0 }} events
                </span>
                <span class="text-blue-600 dark:text-blue-400 text-sm font-medium">
                  View Events →
                </span>
              </div>
            </div>
          </NuxtLink>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import {
  CalendarDaysIcon,
  UserGroupIcon,
  ShieldCheckIcon,
  CheckCircleIcon,
  BuildingOffice2Icon,
  ChartPieIcon,
  CursorArrowRaysIcon,
  UserCircleIcon,
  CogIcon,
  ExclamationCircleIcon,
  ArrowPathIcon,
  ChatBubbleLeftIcon,
} from '@heroicons/vue/24/outline'

useHead({
  title: 'Solutechify - Smart Event Management Platform',
  meta: [
    { name: 'description', content: 'The smart event management platform built for modern organizations. Create, manage, and track your events with ease and professionalism.' }
  ]
})

const config = useRuntimeConfig()

// Fetch organizations with refresh function
const { data: organizations, pending, error, refresh: refreshOrganizations } = await useFetch('/organizations', {
  baseURL: config.public.apiBase,
  server: false,
  transform: (data: any) => {
    return Array.isArray(data) ? data : []
  },
  onResponseError: (error) => {
    console.error('API Error:', error)
    return {
      message: error.response?.status === 404 
        ? 'The server endpoint was not found. Please try again later.'
        : error.response?.status >= 500
        ? 'We are experiencing server issues. Our team has been notified.'
        : 'Unable to load organizations. Please try again.'
    }
  },
  onRequestError: ({ error }) => {
    console.error('Request Error:', error)
    return {
      message: error instanceof Error && error.message === 'Failed to fetch'
        ? 'Unable to connect to the server. Please check your internet connection.'
        : 'An unexpected error occurred. Please try again.'
    }
  }
})

const features = [
  {
    name: 'Multi-Tenant Architecture',
    description: 'Securely manage multiple organizations with isolated data and customizable public pages.',
    icon: BuildingOffice2Icon,
  },
  {
    name: 'Intuitive Event Creation',
    description: 'Easily create and manage events with rich details, ticketing options, and attendee limits.',
    icon: CalendarDaysIcon,
  },
  {
    name: 'Seamless Attendee Management',
    description: 'Track registrations in real-time, send automated confirmations, and manage attendee information efficiently.',
    icon: UserGroupIcon,
  },
  {
    name: 'Powerful Analytics',
    description: 'Gain valuable insights into your event performance, attendance patterns, and revenue with clear dashboards.',
    icon: ChartPieIcon,
  },
  {
    name: 'Robust Security & Roles',
    description: 'Role-based access control and secure authentication to protect your organization and event data.',
    icon: ShieldCheckIcon,
  },
  {
    name: 'Customizable Event Pages',
    description: 'Design beautiful, branded public-facing pages for your events to attract more attendees.',
    icon: UserGroupIcon,
  }
]

const steps = [
  {
    name: '1. Sign Up & Setup',
    description: 'Quickly register your organization and configure your primary settings and branding.'
  },
  {
    name: '2. Create Your Events',
    description: 'Utilize our intuitive builder to add event details, set pricing, and define attendee capacities.'
  },
  {
    name: '3. Go Live & Manage',
    description: 'Publish your event pages, manage incoming registrations, and track your success with ease.'
  }
]

const plans = [
  {
    name: 'Starter',
    description: 'Perfect for individuals or small teams just getting started with event management.',
    price: 0,
    features: [
      'Up to 3 Active Events',
      'Max 50 Attendees per Event',
      'Basic Event Management Tools',
      'Standard Email Notifications',
      'Public Event Pages'
    ],
  },
  {
    name: 'Professional',
    description: 'Ideal for growing organizations needing more capacity and customization.',
    price: 4999,
    features: [
      'Unlimited Active Events',
      'Max 250 Attendees per Event',
      'Custom Event Page Branding',
      'Attendee List Exports',
      'Advanced Analytics Dashboard',
      'Priority Email Support'
    ],
  },
  {
    name: 'Enterprise',
    description: 'Tailored for large organizations with complex needs and high volume.',
    price: 9999,
    features: [
      'All Professional Features',
      'Unlimited Attendees',
      'Dedicated Account Manager',
      'Custom Integrations (API Access)',
      'SLA Guarantees',
      'Advanced Security Options'
    ],
  }
]

const handleRefresh = async () => {
  await refreshOrganizations()
}
</script>

<style scoped>
@keyframes float {
  0%, 100% { transform: translateY(0) scale(1); opacity: 0.6; }
  50% { transform: translateY(-20px) scale(1.05); opacity: 0.4; }
}

@keyframes float-delayed {
  0%, 100% { transform: translateY(0) scale(1); opacity: 0.5; }
  50% { transform: translateY(20px) scale(1.03); opacity: 0.3; }
}

@keyframes gradient {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

.animate-float {
  animation: float 8s infinite ease-in-out;
}

.animate-float-delayed {
  animation: float-delayed 10s infinite ease-in-out;
}

.animate-gradient {
  background-size: 200% 200%;
  animation: gradient 8s ease infinite;
}

.animate-fade-in {
  opacity: 0;
  animation: fadeIn 0.8s ease-out forwards;
}

.animate-fade-in-delayed {
  opacity: 0;
  animation: fadeIn 0.8s ease-out 0.3s forwards;
}

.animate-fade-in-delayed-2 {
  opacity: 0;
  animation: fadeIn 0.8s ease-out 0.6s forwards;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in-up-0 { animation: fadeInUp 0.8s ease-out 0.1s forwards; }
.animate-fade-in-up-1 { animation: fadeInUp 0.8s ease-out 0.2s forwards; }
.animate-fade-in-up-2 { animation: fadeInUp 0.8s ease-out 0.3s forwards; }
.animate-fade-in-up-3 { animation: fadeInUp 0.8s ease-out 0.4s forwards; }
.animate-fade-in-up-4 { animation: fadeInUp 0.8s ease-out 0.5s forwards; }
.animate-fade-in-up-5 { animation: fadeInUp 0.8s ease-out 0.6s forwards; }

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(40px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.bg-grid-pattern {
  background-image: linear-gradient(to right, rgba(99, 102, 241, 0.1) 1px, transparent 1px),
                    linear-gradient(to bottom, rgba(99, 102, 241, 0.1) 1px, transparent 1px);
  background-size: 24px 24px;
}

.hero-text-responsive {
  font-size: clamp(2.5rem, 5vw, 3.75rem);
}

.feature-card, .pricing-card, .step-card {
  position: relative;
  overflow: hidden;
}

.feature-card::before, .pricing-card::before, .step-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  border-radius: inherit;
  padding: 2px;
  background: linear-gradient(to bottom right, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.05));
  -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
  mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
  -webkit-mask-composite: xor;
  mask-composite: exclude;
  pointer-events: none;
}
</style> 