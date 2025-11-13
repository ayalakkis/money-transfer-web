import { Routes, Route, Navigate } from 'react-router-dom'
import { useAuthStore } from './stores/authStore'

// Layouts
import MainLayout from './layouts/MainLayout'
import AuthLayout from './layouts/AuthLayout'
import DashboardLayout from './layouts/DashboardLayout'

// Public Pages
import HomePage from './pages/HomePage'
import LoginPage from './pages/auth/LoginPage'
import RegisterPage from './pages/auth/RegisterPage'

// Protected Pages - User
import DashboardPage from './pages/dashboard/DashboardPage'
import SendMoneyPage from './pages/dashboard/SendMoneyPage'
import TransactionsPage from './pages/dashboard/TransactionsPage'
import BankAccountsPage from './pages/dashboard/BankAccountsPage'
import PaymentMethodsPage from './pages/dashboard/PaymentMethodsPage'
import BeneficiariesPage from './pages/dashboard/BeneficiariesPage'
import ProfilePage from './pages/dashboard/ProfilePage'

// Protected Route Component
const ProtectedRoute = ({ children }) => {
  const { isAuthenticated } = useAuthStore()
  
  if (!isAuthenticated) {
    return <Navigate to="/login" replace />
  }
  
  return children
}

function App() {
  return (
    <Routes>
      {/* Public Routes */}
      <Route element={<MainLayout />}>
        <Route path="/" element={<HomePage />} />
      </Route>

      {/* Auth Routes */}
      <Route element={<AuthLayout />}>
        <Route path="/login" element={<LoginPage />} />
        <Route path="/register" element={<RegisterPage />} />
      </Route>

      {/* Protected Routes - Dashboard */}
      <Route
        element={
          <ProtectedRoute>
            <DashboardLayout />
          </ProtectedRoute>
        }
      >
        <Route path="/dashboard" element={<DashboardPage />} />
        <Route path="/send-money" element={<SendMoneyPage />} />
        <Route path="/transactions" element={<TransactionsPage />} />
        <Route path="/bank-accounts" element={<BankAccountsPage />} />
        <Route path="/payment-methods" element={<PaymentMethodsPage />} />
        <Route path="/beneficiaries" element={<BeneficiariesPage />} />
        <Route path="/profile" element={<ProfilePage />} />
      </Route>

      {/* 404 */}
      <Route path="*" element={<Navigate to="/" replace />} />
    </Routes>
  )
}

export default App

