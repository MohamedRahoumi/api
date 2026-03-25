<script setup>
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../services/api'

const route = useRoute()
const router = useRouter()

const form = ref({
  title: '',
  content: '',
  location: ''
})

const loading = ref(false)
const error = ref('')

const loadQuestion = async () => {
  loading.value = true
  error.value = ''

  try {
    const response = await api.get(`/questions/${route.params.id}`)
    const data = response.data?.data
    form.value.title = data.title
    form.value.content = data.content
  } catch (err) {
    error.value = 'Impossible de charger la question.'
  } finally {
    loading.value = false
  }
}

const handleSubmit = () => {
  error.value = "La mise a jour n'est pas encore disponible via l'API."
}

onMounted(() => {
  loadQuestion()
})
</script>

<template>
  <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow sm:rounded-lg overflow-hidden">
      <div class="px-4 py-5 sm:px-6 bg-sky-50">
        <h3 class="text-lg leading-6 font-medium text-sky-900">Modifier votre question</h3>
      </div>
      <div class="px-4 py-5 sm:p-6">
        <div v-if="error" class="mb-4 rounded-lg bg-amber-50 text-amber-800 px-4 py-3 text-sm">
          {{ error }}
        </div>

        <form @submit.prevent="handleSubmit">
          <div class="mb-6">
            <label for="title" class="block text-sm font-medium text-gray-700">Titre</label>
            <div class="mt-1 relative rounded-md shadow-sm">
              <input
                id="title"
                v-model="form.title"
                type="text"
                required
                class="focus:ring-sky-500 focus:border-sky-500 block w-full pl-3 pr-12 sm:text-sm border-gray-300 rounded-md py-2"
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
              ></textarea>
            </div>
          </div>

          <div class="mb-6">
            <label for="location" class="block text-sm font-medium text-gray-700">Localisation</label>
            <input
              id="location"
              v-model="form.location"
              type="text"
              class="mt-1 focus:ring-sky-500 focus:border-sky-500 block w-full sm:text-sm border-gray-300 rounded-md py-2 px-3"
            />
          </div>

          <div class="flex justify-end pt-4">
            <button
              type="button"
              class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 mr-3"
              @click="router.push(`/questions/${route.params.id}`)"
            >
              Annuler
            </button>
            <button
              type="submit"
              class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-sky-600 hover:bg-sky-700"
              :disabled="loading"
            >
              Enregistrer les modifications
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
