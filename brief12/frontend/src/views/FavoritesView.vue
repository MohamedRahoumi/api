<script setup>
import { onMounted, ref } from 'vue'
import api from '../services/api'

const favorites = ref([])
const pagination = ref({
  current_page: 1,
  last_page: 1
})
const loading = ref(false)
const error = ref('')

const fetchFavorites = async (page = 1) => {
  loading.value = true
  error.value = ''

  try {
    const response = await api.get('/favorites', {
      params: { page }
    })
    const payload = response.data?.data

    favorites.value = payload?.data || []
    pagination.value = {
      current_page: payload?.current_page || 1,
      last_page: payload?.last_page || 1
    }
  } catch (err) {
    error.value = "Impossible de charger les favoris. Connectez-vous d'abord."
  } finally {
    loading.value = false
  }
}

const toggleFavorite = async (questionId) => {
  try {
    await api.post(`/questions/${questionId}/favorite`)
    favorites.value = favorites.value.filter((item) => item.question_id !== questionId)
  } catch (err) {
    error.value = 'Action impossible.'
  }
}

onMounted(() => {
  fetchFavorites()
})
</script>

<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-gray-900">Mes Questions Favorites</h1>
      <RouterLink to="/questions" class="text-sky-600 hover:text-sky-800">
        <i class="fas fa-arrow-left mr-2"></i> Retour aux questions
      </RouterLink>
    </div>

    <div v-if="loading" class="text-center py-10 text-gray-500">Chargement...</div>
    <div v-else-if="error" class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 text-center">
      {{ error }}
    </div>

    <div v-else class="space-y-6">
      <div
        v-for="favorite in favorites"
        :key="favorite.id"
        class="bg-white shadow overflow-hidden sm:rounded-lg hover:shadow-md transition"
      >
        <div class="p-6">
          <div class="flex items-center justify-between">
            <div class="flex items-center flex-1">
              <div class="shrink-0">
                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-amber-100 text-amber-600 font-bold">
                  {{ (favorite.question?.user?.name || 'A').substring(0, 1).toUpperCase() }}
                </span>
              </div>
              <div class="ml-4 flex-1">
                <h2 class="text-lg font-medium text-sky-600 truncate">
                  <RouterLink :to="`/questions/${favorite.question_id}`" class="hover:underline">
                    {{ favorite.question?.title }}
                  </RouterLink>
                </h2>
                <p class="text-sm text-gray-500">
                  Par {{ favorite.question?.user?.name || 'Anonyme' }}
                </p>
              </div>
            </div>
            <div class="flex items-center space-x-4 text-sm text-gray-500 ml-4">
              <button
                type="button"
                class="text-amber-500 hover:text-gray-500 transition"
                title="Retirer des favoris"
                @click="toggleFavorite(favorite.question_id)"
              >
                <i class="fas fa-star"></i>
              </button>
            </div>
          </div>
          <div class="mt-4">
            <p class="text-gray-600 line-clamp-2">
              {{ favorite.question?.content }}
            </p>
          </div>
        </div>
      </div>

      <div v-if="favorites.length === 0" class="text-center py-12 bg-white rounded-lg shadow">
        <i class="fas fa-star text-gray-300 text-5xl mb-4"></i>
        <p class="text-gray-500 text-lg">Vous n'avez pas encore de questions favorites.</p>
        <RouterLink to="/questions" class="text-sky-600 hover:text-sky-800 font-medium mt-2 inline-block">
          Explorez les questions et ajoutez vos favorites !
        </RouterLink>
      </div>
    </div>

    <div v-if="pagination.last_page > 1" class="flex justify-center gap-2 mt-8">
      <button
        @click="fetchFavorites(pagination.current_page - 1)"
        :disabled="pagination.current_page === 1"
        class="px-4 py-2 border rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
      >
        Precedent
      </button>

      <span class="px-4 py-2 text-gray-600">
        Page {{ pagination.current_page }} sur {{ pagination.last_page }}
      </span>

      <button
        @click="fetchFavorites(pagination.current_page + 1)"
        :disabled="pagination.current_page === pagination.last_page"
        class="px-4 py-2 border rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
      >
        Suivant
      </button>
    </div>
  </div>
</template>
