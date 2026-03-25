<script setup>
import { computed, ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '../services/api'
import authStore from '../stores/auth'

const router = useRouter()
const isAuthenticated = computed(() => authStore.isAuthenticated.value)

const form = ref({
  title: '',
  content: '',
  location: ''
})

const error = ref('')
const loading = ref(false)

const handleSubmit = async () => {
  if (!isAuthenticated.value) {
    error.value = 'Vous devez etre connecte pour publier une question.'
    return
  }

  loading.value = true
  error.value = ''

  try {
    const response = await api.post('/questions', {
      title: form.value.title,
      content: form.value.content
    })
    router.push(`/questions/${response.data.data.id}`)
  } catch (err) {
    error.value = 'Impossible de publier la question.'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow sm:rounded-lg overflow-hidden">
      <div class="px-4 py-5 sm:px-6 bg-sky-50">
        <h3 class="text-lg leading-6 font-medium text-sky-900">Poser une nouvelle question</h3>
        <p class="mt-1 max-w-2xl text-sm text-sky-700">
          Partagez vos interrogations avec la communaute locale.
        </p>
      </div>
      <div class="px-4 py-5 sm:p-6">
        <div v-if="error" class="mb-4 rounded-lg bg-red-50 text-red-700 px-4 py-3 text-sm">
          {{ error }}
        </div>

        <form @submit.prevent="handleSubmit">
          <div class="mb-6">
            <label for="title" class="block text-sm font-medium text-gray-700">Titre de votre question</label>
            <div class="mt-1 relative rounded-md shadow-sm">
              <input
                id="title"
                v-model="form.title"
                type="text"
                required
                class="focus:ring-sky-500 focus:border-sky-500 block w-full pl-3 pr-12 sm:text-sm border-gray-300 rounded-md py-2"
                placeholder="Ex: Ou trouver le meilleur cafe a..."
              />
            </div>
          </div>

          <div class="mb-6">
            <label for="content" class="block text-sm font-medium text-gray-700">Details</label>
            <div class="mt-1">
              <textarea
                id="content"
                v-model="form.content"
                rows="5"
                required
                class="shadow-sm focus:ring-sky-500 focus:border-sky-500 block w-full sm:text-sm border-gray-300 rounded-md p-3"
                placeholder="Decrivez votre question en detail..."
              ></textarea>
            </div>
          </div>

          <div class="mb-6">
            <label for="location" class="block text-sm font-medium text-gray-700">Localisation (Optionnel)</label>
            <input
              id="location"
              v-model="form.location"
              type="text"
              class="mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3"
              placeholder="Ex: Centre-ville, Quartier Nord..."
            />
          </div>

          <div class="flex justify-end pt-4">
            <RouterLink
              to="/questions"
              class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 mr-3"
            >
              Annuler
            </RouterLink>
            <button
              type="submit"
              class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-sky-600 hover:bg-sky-700"
              :disabled="loading"
            >
              {{ loading ? 'Publication...' : 'Publier ma question' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
