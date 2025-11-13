import { create } from 'zustand'
import { authAPI } from '../lib/api'
import toast from 'react-hot-toast'

// Mock mode - set to true when no backend is available
const MOCK_MODE = true

export const useAuthStore = create((set) => ({
  user: JSON.parse(localStorage.getItem('user')) || null,
  token: localStorage.getItem('token') || null,
  isAuthenticated: !!localStorage.getItem('token'),
  loading: false,

  login: async (credentials) => {
    set({ loading: true })
    
    // Mock login (frontend only)
    if (MOCK_MODE) {
      // Simulate API delay
      await new Promise(resolve => setTimeout(resolve, 500))
      
      const mockUser = {
        id: '1',
        email: credentials.email,
        first_name: 'Demo',
        last_name: 'User',
        is_verified: true
      }
      const mockToken = 'mock-token-' + Date.now()
      
      localStorage.setItem('token', mockToken)
      localStorage.setItem('user', JSON.stringify(mockUser))
      
      set({ user: mockUser, token: mockToken, isAuthenticated: true, loading: false })
      toast.success('Welcome back! (Demo Mode)')
      return true
    }
    
    // Real API call
    try {
      const response = await authAPI.login(credentials)
      const { token, user } = response.data
      
      localStorage.setItem('token', token)
      localStorage.setItem('user', JSON.stringify(user))
      
      set({ user, token, isAuthenticated: true, loading: false })
      toast.success('Welcome back!')
      return true
    } catch (error) {
      set({ loading: false })
      toast.error('Login failed. Check your credentials.')
      return false
    }
  },

  register: async (data) => {
    set({ loading: true })
    
    // Mock registration (frontend only)
    if (MOCK_MODE) {
      // Simulate API delay
      await new Promise(resolve => setTimeout(resolve, 800))
      
      const mockUser = {
        id: '1',
        email: data.email,
        first_name: data.first_name,
        last_name: data.last_name,
        phone: data.phone,
        is_verified: true
      }
      const mockToken = 'mock-token-' + Date.now()
      
      localStorage.setItem('token', mockToken)
      localStorage.setItem('user', JSON.stringify(mockUser))
      
      set({ user: mockUser, token: mockToken, isAuthenticated: true, loading: false })
      toast.success('Account created successfully! (Demo Mode)')
      return true
    }
    
    // Real API call
    try {
      const response = await authAPI.register(data)
      const { token, user } = response.data
      
      localStorage.setItem('token', token)
      localStorage.setItem('user', JSON.stringify(user))
      
      set({ user, token, isAuthenticated: true, loading: false })
      toast.success('Account created successfully!')
      return true
    } catch (error) {
      set({ loading: false })
      toast.error('Registration failed. Please try again.')
      return false
    }
  },

  logout: async () => {
    if (!MOCK_MODE) {
      try {
        await authAPI.logout()
      } catch (error) {
        // Continue with logout even if API call fails
      }
    }
    
    localStorage.removeItem('token')
    localStorage.removeItem('user')
    set({ user: null, token: null, isAuthenticated: false })
    toast.success('Logged out successfully')
  },

  updateUser: (userData) => {
    localStorage.setItem('user', JSON.stringify(userData))
    set({ user: userData })
  },
}))

