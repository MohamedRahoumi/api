<script setup>
import { computed, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../services/api'
import authStore from '../stores/auth'

const route = useRoute()
const router = useRouter()

const question = ref(null)
const loading = ref(false)
const error = ref('')
const responseContent = ref('')

const isAuthenticated = computed(() => authStore.isAuthenticated.value)
const currentUserId = computed(() => authStore.state.user?.id)
const isAdmin = computed(() => authStore.isAdmin.value)

const canManageQuestion = computed(() => {
  return question.value && (question.value.user_id === currentUserId.value || isAdmin.value)
})

const loadQuestion = async () => {
  loading.value = true
  error.value = ''

  try {
    const response = await api.get(`/questions/${route.params.id}`)
    const data = response.data?.data

    question.value = {
      ...data,
      __liked: currentUserId.value
        ? (data.likes || []).some((like) => like.user_id === currentUserId.value)
        : false,
      __likesCount: data.likes ? data.likes.length : 0,
      __favorited: currentUserId.value
        ? (data.favorites || []).some((fav) => fav.user_id === currentUserId.value)
        : false
    }
  } catch (err) {
    error.value = 'Impossible de charger la question.'
  } finally {
    loading.value = false
  }
}

const toggleLike = async () => {
  if (!question.value || !isAuthenticated.value) {
    return
  }

  try {
    const response = await api.post(`/questions/${question.value.id}/like`)
    question.value.__liked = response.data?.data?.liked ?? question.value.__liked
    question.value.__likesCount = response.data?.data?.likes_count ?? question.value.__likesCount
  } catch (err) {
    error.value = 'Impossible de mettre a jour le like.'
  }
}

const toggleFavorite = async () => {
  if (!question.value || !isAuthenticated.value) {
    return
  }

  try {
    const response = await api.post(`/questions/${question.value.id}/favorite`)
    question.value.__favorited = response.data?.data?.favorited ?? question.value.__favorited
  } catch (err) {
    error.value = 'Impossible de mettre a jour le favori.'
  }
}

const addResponse = async () => {
  if (!responseContent.value.trim()) {
    return
  }

  try {
    const response = await api.post(`/questions/${question.value.id}/responses`, {
      content: responseContent.value
    })
    question.value.responses = [response.data.data, ...(question.value.responses || [])]
    responseContent.value = ''
  } catch (err) {
    error.value = 'Impossible de publier la reponse.'
  }
}

const deleteResponse = async (responseId) => {
  try {
    await api.delete(`/responses/${responseId}`)
    question.value.responses = question.value.responses.filter((item) => item.id !== responseId)
  } catch (err) {
    error.value = 'Suppression impossible.'
  }
}

const deleteQuestion = async () => {
  if (!question.value) {
    return
  }

  try {
    await api.delete(`/questions/${question.value.id}`)
    router.push('/questions')
  } catch (err) {
    error.value = 'Suppression impossible.'
  }
}

const canManageResponse = (response) => {
  return response.user_id === currentUserId.value || isAdmin.value
}

onMounted(() => {
  loadQuestion()
})
</script>

<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div v-if="loading" class="text-center py-10 text-gray-500">Chargement...</div>
    <div v-else-if="error" class="bg-red-100 text-red-700 p-4 rounded-lg mb-6 text-center">
      {{ error }}
    </div>

    <div v-else-if="question" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <div class="lg:col-span-2 space-y-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
          <div class="px-4 py-5 sm:px-6 flex justify-between items-start">
            <div>
              <h1 class="text-2xl font-bold text-gray-900">{{ question.title }}</h1>
              <div class="mt-1 flex items-center text-sm text-gray-500">
                <span class="mr-3">Par {{ question.user?.name || 'Anonyme' }}</span>
                <span>{{ new Date(question.created_at).toLocaleDateString('fr-FR') }}</span>
              </div>
            </div>

            <div v-if="canManageQuestion" class="flex space-x-2">
              <RouterLink
                :to="`/questions/${question.id}/edit`"
                class="text-sky-600 hover:text-sky-900 border border-sky-200 rounded px-2 py-1 text-sm"
              >
                Editer
              </RouterLink>
              <button
                type="button"
                class="text-red-600 hover:text-red-900 border border-red-200 rounded px-2 py-1 text-sm"
                @click="deleteQuestion"
              >
                Supprimer
              </button>
            </div>
          </div>

          <div class="px-4 py-5 sm:p-6 border-t border-gray-200">
            <div class="prose max-w-none text-gray-800 whitespace-pre-line">
              {{ question.content }}
            </div>
          </div>

          <div class="bg-gray-50 px-4 py-4 sm:px-6 flex justify-between items-center border-t border-gray-200">
            <div class="flex space-x-4">
              <button
                v-if="isAuthenticated"
                type="button"
                class="text-gray-500 hover:text-sky-600 transition"
                :class="{ 'text-sky-600': question.__liked }"
                @click="toggleLike"
              >
                <i class="fas fa-thumbs-up"></i>
                {{ question.__liked ? 'Aime' : 'Aimer' }} ({{ question.__likesCount }})
              </button>
              <button
                v-if="isAuthenticated"
                type="button"
                class="text-gray-500 hover:text-amber-500 transition"
                :class="{ 'text-amber-500': question.__favorited }"
                @click="toggleFavorite"
              >
                <i class="fas fa-star"></i>
                {{ question.__favorited ? 'Favori' : 'Ajouter aux favoris' }}
              </button>
            </div>
          </div>
        </div>

        <div class="bg-white shadow sm:rounded-lg overflow-hidden">
          <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
              {{ question.responses?.length || 0 }} Reponses
            </h3>
          </div>

          <ul class="divide-y divide-gray-200">
            <li v-for="response in question.responses || []" :key="response.id" class="p-4 sm:p-6">
              <div class="flex space-x-3">
                <div class="shrink-0">
                  <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-gray-200 text-gray-500 font-bold">
                    {{ (response.user?.name || 'U').substring(0, 1).toUpperCase() }}
                  </span>
                </div>
                <div class="flex-1 space-y-1">
                  <div class="flex items-center justify-between">
                    <h3 class="text-sm font-medium text-gray-900">{{ response.user?.name || 'Anonyme' }}</h3>
                    <p class="text-sm text-gray-500">
                      {{ new Date(response.created_at).toLocaleDateString('fr-FR') }}
                    </p>
                  </div>
                  <div class="text-sm text-gray-700 whitespace-pre-line">
                    {{ response.content }}
                  </div>
                  <div v-if="canManageResponse(response)" class="mt-2 text-right">
                    <button type="button" class="text-xs text-red-500 hover:text-red-700" @click="deleteResponse(response.id)">
                      Supprimer
                    </button>
                  </div>
                </div>
              </div>
            </li>
            <li v-if="!question.responses || question.responses.length === 0" class="p-8 text-center text-gray-500">
              Aucune reponse pour le moment. Soyez le premier a repondre !
            </li>
          </ul>

          <div v-if="isAuthenticated" class="bg-gray-50 px-4 py-6 sm:px-6 border-t border-gray-200">
            <h4 class="text-sm font-medium text-gray-900 mb-3">Votre reponse</h4>
            <form @submit.prevent="addResponse">
              <div>
                <label for="content" class="sr-only">Reponse</label>
                <textarea
                  id="content"
                  v-model="responseContent"
                  rows="3"
                  class="shadow-sm block w-full focus:ring-sky-500 focus:border-sky-500 sm:text-sm border-gray-300 rounded-md p-2"
                  placeholder="Partagez votre avis ou votre solution..."
                ></textarea>
              </div>
              <div class="mt-3 flex justify-end">
                <button
                  type="submit"
                  class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-sky-600 hover:bg-sky-700"
                >
                  Publier la reponse
                </button>
              </div>
            </form>
          </div>
          <div v-else class="bg-gray-50 px-4 py-6 sm:px-6 border-t border-gray-200 text-center">
            <p class="text-gray-600">
              <RouterLink to="/login" class="text-sky-600 font-medium hover:underline">Connectez-vous</RouterLink>
              pour repondre a cette question.
            </p>
          </div>
        </div>
      </div>

      <div class="space-y-6">
        <div class="bg-white shadow sm:rounded-lg overflow-hidden">
          <div class="px-4 py-5 sm:px-6 bg-gray-50">
            <h3 class="text-lg font-medium text-gray-900">Conseils</h3>
          </div>
          <div class="px-4 py-5 sm:p-6 text-sm text-gray-500 space-y-3">
            <p><i class="fas fa-check-circle text-emerald-500 mr-2"></i> Soyez precis et courtois.</p>
            <p><i class="fas fa-check-circle text-emerald-500 mr-2"></i> Verifiez si la question n'a pas deja ete posee.</p>
            <p><i class="fas fa-check-circle text-emerald-500 mr-2"></i> Partagez votre propre experience locale.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
