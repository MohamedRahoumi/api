<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import authStore from '../stores/auth'

const router = useRouter()

const form = ref({
  name: '',
  email: '',
  location: '',
  password: '',
  passwordConfirmation: ''
})

const error = ref('')
const loading = ref(false)

const handleSubmit = async () => {
  error.value = ''
  loading.value = true

  try {
    await authStore.register({
      name: form.value.name,
      email: form.value.email,
      password: form.value.password,
      password_confirmation: form.value.passwordConfirmation
    })
    router.push('/questions')
  } catch (err) {
    error.value = 'Impossible de creer le compte. Verifiez les informations.'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="max-w-md mx-auto px-4">
    <div class="bg-white rounded-lg shadow-md p-8">
      <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Inscription</h2>

      <div v-if="error" class="mb-4 rounded-lg bg-red-50 text-red-700 px-4 py-3 text-sm">
        {{ error }}
      </div>

      <form @submit.prevent="handleSubmit">
        <div class="mb-4">
          <label for="name" class="block text-gray-700 text-sm font-bold mb-2">
            Nom complet <span class="text-red-500">*</span>
          </label>
          <input
            id="name"
            v-model="form.name"
            type="text"
            required
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          />
        </div>

        <div class="mb-4">
          <label for="email" class="block text-gray-700 text-sm font-bold mb-2">
            Email <span class="text-red-500">*</span>
          </label>
          <input
            id="email"
            v-model="form.email"
            type="email"
            required
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          />
        </div>

        <div class="mb-4">
          <label for="location" class="block text-gray-700 text-sm font-bold mb-2">Ville / Localisation</label>
          <input
            id="location"
            v-model="form.location"
            type="text"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          />
          <p class="text-gray-600 text-xs italic mt-1">Ex: Casablanca, Maroc</p>
        </div>

        <div class="mb-4">
          <label for="password" class="block text-gray-700 text-sm font-bold mb-2">
            Mot de passe <span class="text-red-500">*</span>
          </label>
          <input
            id="password"
            v-model="form.password"
            type="password"
            required
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          />
        </div>

        <div class="mb-6">
          <label for="passwordConfirmation" class="block text-gray-700 text-sm font-bold mb-2">
            Confirmer le mot de passe <span class="text-red-500">*</span>
          </label>
          <input
            id="passwordConfirmation"
            v-model="form.passwordConfirmation"
            type="password"
            required
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          />
        </div>

        <div class="flex items-center justify-between">
          <button
            type="submit"
            class="bg-sky-600 hover:bg-sky-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full"
            :disabled="loading"
          >
            {{ loading ? 'Inscription...' : "S'inscrire" }}
          </button>
        </div>

        <div class="text-center mt-4">
          <RouterLink to="/login" class="text-sky-600 hover:text-sky-800 text-sm">
            Deja inscrit ? Se connecter
          </RouterLink>
        </div>
      </form>
    </div>
  </div>
</template>
