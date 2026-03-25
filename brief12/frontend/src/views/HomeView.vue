<template>
  <div class="max-w-5xl mx-auto px-6">
    <div class="bg-white shadow-lg rounded-2xl overflow-hidden">
      <div class="grid grid-cols-1 lg:grid-cols-2">
        <div class="p-10 lg:p-12">
          <p class="uppercase tracking-[0.3em] text-xs text-gray-400">Bienvenue</p>
          <h1 class="mt-4 text-3xl lg:text-4xl font-bold text-gray-900">
            Decouvrez les questions locales qui font bouger votre ville.
          </h1>
          <p class="mt-4 text-gray-600">
            Posez des questions, partagez des conseils et creez des liens utiles avec
            votre communaute.
          </p>
          <div class="mt-8 flex flex-wrap gap-3">
            <RouterLink
              to="/questions"
              class="inline-flex items-center px-5 py-2.5 rounded-full bg-sky-600 text-white text-sm font-semibold shadow hover:bg-sky-700"
            >
              Explorer les questions
            </RouterLink>
            <RouterLink
              to="/register"
              class="inline-flex items-center px-5 py-2.5 rounded-full border border-gray-200 text-gray-700 text-sm font-semibold hover:border-gray-300"
            >
              Rejoindre la communaute
            </RouterLink>
          </div>
        </div>
        <div class="relative bg-gradient-to-br from-sky-100 via-white to-amber-100 p-10 lg:p-12">
          <div class="absolute inset-0 opacity-40">
            <div class="absolute -top-10 -left-10 h-40 w-40 rounded-full bg-sky-200"></div>
            <div class="absolute bottom-10 right-6 h-24 w-24 rounded-full bg-amber-200"></div>
          </div>
          <div class="relative space-y-6">
            <div class="bg-white shadow-md rounded-xl p-6">
              <p class="text-xs uppercase tracking-[0.2em] text-gray-400">Idee locale</p>
              <h2 class="mt-2 text-lg font-semibold text-gray-900">
                Ou trouver le meilleur cafe du quartier ?
              </h2>
              <p class="mt-2 text-sm text-gray-500">12 reponses • 3 favoris</p>
            </div>
            <div class="bg-white/90 shadow-sm rounded-xl p-6 border border-white">
              <p class="text-xs uppercase tracking-[0.2em] text-gray-400">Conseil</p>
              <p class="mt-2 text-sm text-gray-600">
                Ajoutez un maximum de details pour aider la communaute a repondre plus vite.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>