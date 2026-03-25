import { computed, reactive, readonly } from 'vue'
import api from '../services/api'

const TOKEN_KEY = 'localmind_token'
const USER_KEY = 'localmind_user'

const state = reactive({
  token: localStorage.getItem(TOKEN_KEY),
  user: JSON.parse(localStorage.getItem(USER_KEY) || 'null'),
  loading: false
})

const persistAuth = (token, user) => {
  state.token = token
  state.user = user
  localStorage.setItem(TOKEN_KEY, token)
  localStorage.setItem(USER_KEY, JSON.stringify(user))
}

const clearAuth = () => {
  state.token = null
  state.user = null
  localStorage.removeItem(TOKEN_KEY)
  localStorage.removeItem(USER_KEY)
}

const init = async () => {
  if (!state.token) {
    return
  }

  state.loading = true
  try {
    const response = await api.get('/me')
    state.user = response.data.user
    localStorage.setItem(USER_KEY, JSON.stringify(state.user))
  } catch (error) {
    clearAuth()
  } finally {
    state.loading = false
  }
}

const login = async (payload) => {
  const response = await api.post('/login', payload)
  persistAuth(response.data.access_token, response.data.user)
  return response.data
}

const register = async (payload) => {
  const response = await api.post('/register', payload)
  persistAuth(response.data.access_token, response.data.user)
  return response.data
}

const logout = async () => {
  try {
    await api.post('/logout')
  } finally {
    clearAuth()
  }
}

const isAuthenticated = computed(() => Boolean(state.token))
const isAdmin = computed(() => state.user?.role === 'admin')

const updateLocalProfile = (payload) => {
  state.user = {
    ...state.user,
    ...payload
  }
  localStorage.setItem(USER_KEY, JSON.stringify(state.user))
}

export default {
  state: readonly(state),
  init,
  login,
  register,
  logout,
  isAuthenticated,
  isAdmin,
  updateLocalProfile
}
