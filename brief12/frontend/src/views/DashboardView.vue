<script setup>
import { ref } from 'vue'

const stats = ref({
  total_users: 0,
  total_questions: 0,
  total_responses: 0,
  recent_questions: [],
  popular_questions: []
})
</script>

<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6 rounded-lg bg-amber-50 text-amber-800 px-4 py-3 text-sm">
      Les statistiques du tableau de bord seront branchees sur l'API admin.
    </div>
     <h1 class="text-3xl font-bold text-gray-900 mb-8">Tableau de bord Administrateur</h1>

     <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
       <div class="bg-white overflow-hidden shadow rounded-lg">
         <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0 bg-sky-500 rounded-md p-3">
               <i class="fas fa-users text-white text-2xl"></i>
             </div>
             <div class="ml-5 w-0 flex-1">
               <dl>
                 <dt class="text-sm font-medium text-gray-500 truncate">Utilisateurs inscrits</dt>
                 <dd class="text-3xl font-semibold text-gray-900">{{ stats.total_users }}</dd>
               </dl>
             </div>
           </div>
         </div>
       </div>

       <div class="bg-white overflow-hidden shadow rounded-lg">
         <div class="p-5">
           <div class="flex items-center">
             <div class="flex-shrink-0 bg-emerald-500 rounded-md p-3">
               <i class="fas fa-question-circle text-white text-2xl"></i>
             </div>
             <div class="ml-5 w-0 flex-1">
               <dl>
                 <dt class="text-sm font-medium text-gray-500 truncate">Questions posees</dt>
                 <dd class="text-3xl font-semibold text-gray-900">{{ stats.total_questions }}</dd>
               </dl>
             </div>
           </div>
         </div>
       </div>

       <div class="bg-white overflow-hidden shadow rounded-lg">
         <div class="p-5">
           <div class="flex items-center">
             <div class="flex-shrink-0 bg-amber-500 rounded-md p-3">
               <i class="fas fa-comments text-white text-2xl"></i>
             </div>
             <div class="ml-5 w-0 flex-1">
               <dl>
                 <dt class="text-sm font-medium text-gray-500 truncate">Reponses totales</dt>
                 <dd class="text-3xl font-semibold text-gray-900">{{ stats.total_responses }}</dd>
               </dl>
             </div>
           </div>
         </div>
       </div>
     </div>

     <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
       <div class="bg-white shadow rounded-lg">
         <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
           <h3 class="text-lg leading-6 font-medium text-gray-900">Questions Recentes</h3>
         </div>
         <ul class="divide-y divide-gray-200">
           <li v-if="stats.recent_questions.length === 0" class="p-4 text-center text-gray-500">
             Aucune question recente.
           </li>
           <li
             v-for="question in stats.recent_questions"
             :key="question.id"
             class="p-4 hover:bg-gray-50"
           >
             <div class="flex space-x-3">
               <div class="flex-1 space-y-1">
                 <div class="flex items-center justify-between">
                   <h3 class="text-sm font-medium">
                     <RouterLink
                       :to="`/questions/${question.id}`"
                       class="text-sky-600 hover:text-sky-900"
                     >
                       {{ question.title }}
                     </RouterLink>
                   </h3>
                   <p class="text-xs text-gray-500">{{ question.created_at }}</p>
                 </div>
                 <p class="text-sm text-gray-500">Par {{ question.user?.name || 'Anonyme' }}</p>
               </div>
             </div>
           </li>
         </ul>
       </div>

       <div class="bg-white shadow rounded-lg">
         <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
           <h3 class="text-lg leading-6 font-medium text-gray-900">Questions Populaires</h3>
         </div>
         <ul class="divide-y divide-gray-200">
           <li v-if="stats.popular_questions.length === 0" class="p-4 text-center text-gray-500">
             Pas assez de donnees.
           </li>
           <li
             v-for="question in stats.popular_questions"
             :key="question.id"
             class="p-4 hover:bg-gray-50"
           >
             <div class="flex justify-between items-center">
               <div>
                 <RouterLink
                   :to="`/questions/${question.id}`"
                   class="text-sky-600 font-medium hover:text-sky-900"
                 >
                   {{ question.title }}
                 </RouterLink>
                 <p class="text-xs text-gray-400 mt-1">
                   <i class="fas fa-comment-alt mr-1"></i> {{ question.responses_count }} reponses
                 </p>
               </div>
               <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                 Populaire
               </span>
             </div>
           </li>
         </ul>
       </div>
     </div>
   </div>
 </template>
