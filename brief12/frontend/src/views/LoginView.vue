<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import authStore from '../stores/auth'

const router = useRouter()

const form = ref({
  email: '',
  password: '',
  remember: false
})

const error = ref('')
const loading = ref(false)

const handleSubmit = async () => {
  error.value = ''
  loading.value = true

  try {
    await authStore.login({
      email: form.value.email,
      password: form.value.password
    })
    router.push('/questions')
  } catch (err) {
    error.value = 'Identifiants invalides. Merci de reessayer.'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="max-w-md mx-auto px-4">
    <div class="bg-white rounded-lg shadow-md p-8">
      <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Connexion</h2>

      <div v-if="error" class="mb-4 rounded-lg bg-red-50 text-red-700 px-4 py-3 text-sm">
        {{ error }}
      </div>

      <form @submit.prevent="handleSubmit">
        <div class="mb-4">
          <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
          <input
            id="email"
            v-model="form.email"
            type="email"
            required
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          />
        </div>

        <div class="mb-6">
          <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Mot de passe</label>
          <input
            id="password"
            v-model="form.password"
            type="password"
            required
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
          />
        </div>

        <div class="mb-6">
          <label class="flex items-center">
            <input v-model="form.remember" type="checkbox" class="form-checkbox h-4 w-4 text-sky-600" />
            <span class="ml-2 text-sm text-gray-700">Se souvenir de moi</span>
          </label>
        </div>

        <div class="flex items-center justify-between">
          <button
            type="submit"
            class="bg-sky-600 hover:bg-sky-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full"
            :disabled="loading"
          >
            {{ loading ? 'Connexion...' : 'Se connecter' }}
          </button>
        </div>

        <div class="text-center mt-4">
          <RouterLink to="/register" class="text-sky-600 hover:text-sky-800 text-sm">
            Pas encore inscrit ? Creer un compte
          </RouterLink>
        </div>
      </form>
    </div>
  </div>
</template>
