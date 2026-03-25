<script setup>
import { computed, ref } from 'vue'
import authStore from '../stores/auth'

const user = computed(() => authStore.state.user)

const form = ref({
  name: user.value?.name || '',
  email: user.value?.email || '',
  location: user.value?.location || '',
  currentPassword: '',
  newPassword: '',
  newPasswordConfirmation: ''
})

const success = ref('')

const handleSubmit = () => {
  success.value = 'Profil mis a jour localement. Branchez la mise a jour API pour persister.'
  authStore.updateLocalProfile({
    name: form.value.name,
    email: form.value.email,
    location: form.value.location
  })
}
</script>

<template>
  <div class="max-w-3xl mx-auto px-4">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Mon profil</h1>

    <div class="bg-white rounded-lg shadow-md p-8">
      <div v-if="success" class="mb-4 rounded-lg bg-emerald-50 text-emerald-800 px-4 py-3 text-sm">
        {{ success }}
      </div>

      <form @submit.prevent="handleSubmit">
        <div class="mb-4">
          <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nom complet</label>
          <input
            id="name"
            v-model="form.name"
            type="text"
            required
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"
          />
        </div>

        <div class="mb-4">
          <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
          <input
            id="email"
            v-model="form.email"
            type="email"
            required
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"
          />
        </div>

        <div class="mb-4">
          <label for="location" class="block text-gray-700 text-sm font-bold mb-2">Localisation</label>
          <input
            id="location"
            v-model="form.location"
            type="text"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"
          />
        </div>

        <hr class="my-6" />

        <h3 class="text-lg font-semibold mb-4">Changer le mot de passe</h3>

        <div class="mb-4">
          <label for="current_password" class="block text-gray-700 text-sm font-bold mb-2">Mot de passe actuel</label>
          <input
            id="current_password"
            v-model="form.currentPassword"
            type="password"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"
          />
        </div>

        <div class="mb-4">
          <label for="new_password" class="block text-gray-700 text-sm font-bold mb-2">Nouveau mot de passe</label>
          <input
            id="new_password"
            v-model="form.newPassword"
            type="password"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"
          />
        </div>

        <div class="mb-6">
          <label for="new_password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">
            Confirmer le nouveau mot de passe
          </label>
          <input
            id="new_password_confirmation"
            v-model="form.newPasswordConfirmation"
            type="password"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"
          />
        </div>

        <div class="flex items-center justify-between">
          <button type="submit" class="bg-sky-600 hover:bg-sky-700 text-white font-bold py-2 px-6 rounded">
            Mettre a jour le profil
          </button>
          <RouterLink to="/questions" class="text-gray-600 hover:text-gray-800">Annuler</RouterLink>
        </div>
      </form>
    </div>
  </div>
</template>
