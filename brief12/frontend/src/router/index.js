import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import QuestionsView from '../views/QuestionsView.vue'
import QuestionShowView from '../views/QuestionShowView.vue'
import QuestionCreateView from '../views/QuestionCreateView.vue'
import QuestionEditView from '../views/QuestionEditView.vue'
import LoginView from '../views/LoginView.vue'
import RegisterView from '../views/RegisterView.vue'
import FavoritesView from '../views/FavoritesView.vue'
import ProfileEditView from '../views/ProfileEditView.vue'
import DashboardView from '../views/DashboardView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      redirect: '/questions'
    },
    {
      path: '/welcome',
      name: 'welcome',
      component: HomeView
    },
    {
      path: '/questions',
      name: 'questions',
      component: QuestionsView
    },
    {
      path: '/questions/create',
      name: 'questions-create',
      component: QuestionCreateView
    },
    {
      path: '/questions/:id',
      name: 'questions-show',
      component: QuestionShowView
    },
    {
      path: '/questions/:id/edit',
      name: 'questions-edit',
      component: QuestionEditView
    },
    {
      path: '/login',
      name: 'login',
      component: LoginView
    },
    {
      path: '/register',
      name: 'register',
      component: RegisterView
    },
    {
      path: '/favorites',
      name: 'favorites',
      component: FavoritesView
    },
    {
      path: '/profile',
      name: 'profile',
      component: ProfileEditView
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: DashboardView
    }
  ]
})

export default router