<script setup>
import { computed, onMounted, ref } from 'vue'
import { RouterLink } from 'vue-router'
import api from '../services/api'
import authStore from '../stores/auth'

const questions = ref([])
const loading = ref(false)
const error = ref('')
const searchQuery = ref('')

const isAuthenticated = computed(() => authStore.isAuthenticated.value)
const currentUserId = computed(() => authStore.state.user?.id)

const normalizeQuestion = (question) => {
  const likes = question.likes || []
  const responses = question.responses || []

  return {
    ...question,
    __likesCount: likes.length,
    __responsesCount: responses.length,
    __liked: currentUserId.value
      ? likes.some((like) => like.user_id === currentUserId.value)
      : false
  }
}

const fetchQuestions = async () => {
  loading.value = true
  error.value = ''

  try {
    const response = await api.get('/questions', {
      params: {
        search: searchQuery.value || undefined
      }
    })

    const data = response.data?.data || []
    questions.value = data.map(normalizeQuestion)
  } catch (err) {
    error.value = 'Impossible de charger les questions.'
  } finally {
    loading.value = false
  }
}

const handleSearch = () => {
  fetchQuestions()
}

const toggleLike = async (question) => {
  if (!isAuthenticated.value) {
    return
  }

  try {
    const response = await api.post(`/questions/${question.id}/like`)
    question.__liked = response.data?.data?.liked ?? question.__liked
    question.__likesCount = response.data?.data?.likes_count ?? question.__likesCount
  } catch (err) {
    error.value = 'Impossible de mettre a jour le like.'
  }
}

const formatDate = (dateValue) => {
  return new Date(dateValue).toLocaleDateString('fr-FR')
}

onMounted(() => {
  fetchQuestions()
})
</script>

<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">Questions Locales</h1>
        <p class="text-sm text-gray-500 mt-1">{{ questions.length }} questions partagees</p>
      </div>
      <RouterLink
        v-if="isAuthenticated"
        to="/questions/create"
        class="bg-sky-600 text-white px-4 py-2 rounded-md hover:bg-sky-700 transition"
      >
        <i class="fas fa-plus mr-2"></i> Poser une question
      </RouterLink>
    </div>

    <div class="mb-8">
      <form class="relative" @submit.prevent="handleSearch">
        <input
          v-model="searchQuery"
          type="text"
          class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-sky-500 shadow-sm"
          placeholder="Rechercher une question..."
        />
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <i class="fas fa-search text-gray-400"></i>
        </div>
        <button
          type="submit"
          class="absolute inset-y-0 right-0 px-4 py-2 bg-gray-100 text-gray-600 rounded-r-lg hover:bg-gray-200 border-l border-gray-300"
        >
          Chercher
        </button>
      </form>
    </div>

    <div v-if="loading" class="text-center py-10 text-gray-500">
      Chargement des questions...
    </div>

    <div v-else-if="error" class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 text-center">
      {{ error }}
    </div>

    <div v-else-if="questions.length === 0" class="text-center py-12 bg-white rounded-lg shadow">
      <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
      <p class="text-gray-500 text-lg">Aucune question trouvee.</p>
      <RouterLink to="/questions/create" class="text-sky-600 hover:text-sky-800 font-medium mt-2 inline-block">
        Soyez le premier a poser une question !
      </RouterLink>
    </div>

    <div v-else class="space-y-6">
      <div
        v-for="question in questions"
        :key="question.id"
        class="bg-white shadow overflow-hidden sm:rounded-lg hover:shadow-md transition"
      >
        <div class="p-6">
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <div class="shrink-0">
                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-sky-100 text-sky-500 font-bold">
                  {{ (question.user?.name || 'A').substring(0, 1).toUpperCase() }}
                </span>
              </div>
              <div class="ml-4">
                <h2 class="text-lg font-medium text-sky-600 truncate">
                  <RouterLink :to="`/questions/${question.id}`" class="hover:underline">
                    {{ question.title }}
                  </RouterLink>
                </h2>
                <p class="text-sm text-gray-500">
                  Par {{ question.user?.name || 'Anonyme' }} • {{ formatDate(question.created_at) }}
                </p>
              </div>
            </div>
            <div class="flex items-center space-x-4 text-sm text-gray-500">
              <div class="flex items-center">
                <i class="fas fa-comment-alt mr-1"></i> {{ question.__responsesCount }}
              </div>
              <button
                v-if="isAuthenticated"
                type="button"
                class="flex items-center text-gray-500 hover:text-sky-600 transition"
                :class="{ 'text-sky-600': question.__liked }"
                @click="toggleLike(question)"
              >
                <i class="fas fa-thumbs-up mr-1"></i>
                {{ question.__likesCount }}
              </button>
              <div v-else class="flex items-center text-gray-400">
                <i class="far fa-thumbs-up mr-1"></i> {{ question.__likesCount }}
              </div>
            </div>
          </div>
          <div class="mt-4">
            <p class="text-gray-600 line-clamp-2">
              {{ question.content }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>