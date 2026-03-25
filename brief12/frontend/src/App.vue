<script setup>
import { computed, onMounted } from 'vue'
import { RouterLink, RouterView, useRouter } from 'vue-router'
import authStore from './stores/auth'

const router = useRouter()
const auth = authStore

const isAuthenticated = computed(() => auth.isAuthenticated.value)
const isAdmin = computed(() => auth.isAdmin.value)
const userName = computed(() => auth.state.user?.name || '')

const handleLogout = async () => {
  await auth.logout()
  router.push('/login')
}

onMounted(() => {
  auth.init()
})
</script>

<template>
  <div class="min-h-screen bg-gray-50 text-gray-900">
    <nav class="bg-white shadow-lg">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex">
            <div class="shrink-0 flex items-center">
              <RouterLink to="/questions" class="text-2xl font-bold text-sky-600">
                <i class="fas fa-map-marker-alt"></i> Questions Locales
              </RouterLink>
            </div>
            <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
              <RouterLink
                to="/questions"
                class="border-sky-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
              >
                Questions
              </RouterLink>
              <RouterLink
                v-if="isAuthenticated"
                to="/favorites"
                class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
              >
                <i class="fas fa-star mr-1"></i> Favoris
              </RouterLink>
            </div>
          </div>
          <div class="hidden sm:ml-6 sm:flex sm:items-center">
            <template v-if="isAuthenticated">
              <RouterLink
                v-if="isAdmin"
                to="/dashboard"
                class="text-gray-700 hover:text-sky-600 px-3 py-2 rounded-md text-sm font-medium"
              >
                <i class="fas fa-chart-bar"></i> Dashboard
              </RouterLink>
              <RouterLink
                to="/questions/create"
                class="bg-sky-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-sky-700 mr-2"
              >
                <i class="fas fa-plus"></i> Poser une question
              </RouterLink>
              <div class="ml-3 relative">
                <div class="flex items-center space-x-4">
                  <RouterLink to="/profile" class="text-gray-700 hover:text-sky-600">
                    <i class="fas fa-user"></i> {{ userName }}
                  </RouterLink>
                  <button type="button" class="text-gray-700 hover:text-red-600" @click="handleLogout">
                    <i class="fas fa-sign-out-alt"></i> Deconnexion
                  </button>
                </div>
              </div>
            </template>
            <template v-else>
              <RouterLink to="/login" class="text-gray-700 hover:text-sky-600 px-3 py-2 rounded-md text-sm font-medium">
                Connexion
              </RouterLink>
              <RouterLink
                to="/register"
                class="bg-sky-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-sky-700 ml-2"
              >
                Inscription
              </RouterLink>
            </template>
          </div>
        </div>
      </div>
    </nav>

    <main class="py-10">
      <RouterView />
    </main>

    <footer class="bg-white mt-12">
      <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <p class="text-center text-gray-500 text-sm">
          &copy; 2024 Questions Locales. Tous droits reserves.
        </p>
      </div>
    </footer>
  </div>
</template>