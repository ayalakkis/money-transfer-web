import { Outlet, Link, useLocation, useNavigate } from 'react-router-dom'
import { 
  Send, 
  LayoutDashboard, 
  CreditCard, 
  Wallet, 
  Users, 
  History, 
  User, 
  LogOut,
  Menu,
  X,
  Bell,
  Search
} from 'lucide-react'
import { useState } from 'react'
import { useAuthStore } from '../stores/authStore'
import DemoBanner from '../components/DemoBanner'
import clsx from 'clsx'

const navigation = [
  { name: 'Dashboards', href: '/dashboard', icon: LayoutDashboard },
  { name: 'Send Money', href: '/send-money', icon: Send },
  { name: 'Transactions', href: '/transactions', icon: History },
  { name: 'Bank Accounts', href: '/bank-accounts', icon: Wallet },
  { name: 'Payment Methods', href: '/payment-methods', icon: CreditCard },
  { name: 'Beneficiaries', href: '/beneficiaries', icon: Users },
]

const DashboardLayout = () => {
  const [sidebarOpen, setSidebarOpen] = useState(false)
  const location = useLocation()
  const navigate = useNavigate()
  const { user, logout } = useAuthStore()

  const handleLogout = async () => {
    await logout()
    navigate('/login')
  }

  return (
    <div className="min-h-screen bg-[#f5f5f9]">
      {/* Demo Mode Banner */}
      <DemoBanner />
      
      {/* Mobile sidebar overlay */}
      {sidebarOpen && (
        <div 
          className="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
          onClick={() => setSidebarOpen(false)}
        />
      )}

      {/* Sidebar - Sneat Style */}
      <div
        className={clsx(
          'fixed inset-y-0 left-0 z-50 w-[260px] bg-white shadow-lg transform transition-transform duration-200 ease-in-out lg:translate-x-0',
          sidebarOpen ? 'translate-x-0' : '-translate-x-full'
        )}
      >
        <div className="flex flex-col h-full">
          {/* Logo - Sneat Style */}
          <div className="flex items-center h-[70px] px-6">
            <Link to="/dashboard" className="flex items-center space-x-3">
              <div className="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                <Send className="w-5 h-5 text-white" />
              </div>
              <span className="text-xl font-bold text-[#566a7f]">sneat</span>
            </Link>
            <button
              onClick={() => setSidebarOpen(false)}
              className="lg:hidden ml-auto text-gray-500"
            >
              <X className="w-5 h-5" />
            </button>
          </div>

          {/* Navigation - Sneat Style */}
          <nav className="flex-1 px-4 py-2 space-y-1 overflow-y-auto">
            <div className="text-xs font-semibold text-[#a1acb8] uppercase tracking-wider px-4 py-3 mt-2">
              Apps & Pages
            </div>
            {navigation.map((item) => {
              const Icon = item.icon
              const isActive = location.pathname === item.href
              
              return (
                <Link
                  key={item.name}
                  to={item.href}
                  onClick={() => setSidebarOpen(false)}
                  className={clsx(
                    'flex items-center px-4 py-2.5 text-[15px] rounded-md transition-all group relative',
                    isActive
                      ? 'bg-primary-50/50 text-primary-600 font-medium shadow-sm'
                      : 'text-[#697a8d] hover:bg-gray-50'
                  )}
                >
                  <Icon className={clsx(
                    "w-[22px] h-[22px] mr-3",
                    isActive ? 'text-primary-600' : 'text-[#697a8d]'
                  )} />
                  {item.name}
                  {isActive && (
                    <div className="absolute right-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-primary-600 rounded-l-md"></div>
                  )}
                </Link>
              )
            })}

            <div className="text-xs font-semibold text-[#a1acb8] uppercase tracking-wider px-4 py-3 mt-6">
              Account
            </div>
            <Link
              to="/profile"
              className={clsx(
                'flex items-center px-4 py-2.5 text-[15px] rounded-md transition-all',
                location.pathname === '/profile'
                  ? 'bg-primary-50/50 text-primary-600 font-medium'
                  : 'text-[#697a8d] hover:bg-gray-50'
              )}
            >
              <User className="w-[22px] h-[22px] mr-3" />
              Account Settings
            </Link>
          </nav>

          {/* User section - Sneat Style */}
          <div className="p-4 border-t border-[#dbdade]">
            <div className="flex items-center p-3 rounded-lg hover:bg-gray-50 cursor-pointer mb-2">
              <div className="w-9 h-9 rounded-full bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center text-white font-semibold text-sm">
                {user?.first_name?.charAt(0)}{user?.last_name?.charAt(0)}
              </div>
              <div className="ml-3 flex-1">
                <p className="text-sm font-medium text-[#566a7f]">
                  {user?.first_name} {user?.last_name}
                </p>
                <p className="text-xs text-[#a1acb8]">User</p>
              </div>
            </div>
            <button
              onClick={handleLogout}
              className="flex items-center w-full px-4 py-2.5 text-[15px] text-danger-600 hover:bg-danger-50 rounded-md transition-all"
            >
              <LogOut className="w-[22px] h-[22px] mr-3" />
              Logout
            </button>
          </div>
        </div>
      </div>

      {/* Main content */}
      <div className="lg:pl-[260px]">
        {/* Top navbar - Sneat Style */}
        <div className="sticky top-0 z-30 bg-white border-b border-[#dbdade] shadow-sm">
          <div className="flex items-center h-[70px] px-6">
            <button
              onClick={() => setSidebarOpen(true)}
              className="lg:hidden text-gray-500 mr-4"
            >
              <Menu className="w-6 h-6" />
            </button>

            {/* Search Bar */}
            <div className="flex-1 max-w-xl">
              <div className="relative">
                <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-[#a1acb8]" />
                <input
                  type="text"
                  placeholder="Search (Ctrl+/)"
                  className="w-full pl-10 pr-4 py-2 bg-[#f5f5f9] border-0 rounded-md text-sm text-[#697a8d] placeholder-[#a1acb8] focus:outline-none focus:ring-2 focus:ring-primary-500"
                />
              </div>
            </div>

            {/* Right side icons */}
            <div className="flex items-center space-x-2 ml-4">
              <button className="relative p-2 text-[#697a8d] hover:bg-gray-50 rounded-lg transition-colors">
                <Bell className="w-5 h-5" />
                <span className="absolute top-1.5 right-1.5 w-2 h-2 bg-danger-500 rounded-full"></span>
              </button>
              <button className="p-2 text-[#697a8d] hover:bg-gray-50 rounded-lg transition-colors">
                <User className="w-5 h-5" />
              </button>
            </div>
          </div>
        </div>

        {/* Page content - Sneat Style */}
        <main className="p-6">
          <Outlet />
        </main>
      </div>
    </div>
  )
}

export default DashboardLayout
