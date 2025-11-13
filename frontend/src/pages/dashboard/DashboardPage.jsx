import { useQuery } from 'react-query'
import { Link } from 'react-router-dom'
import { Send, CreditCard, Users, TrendingUp, ArrowRight, Wallet, Activity, Clock } from 'lucide-react'
import { motion } from 'framer-motion'
import { transactionsAPI, bankAccountsAPI, paymentMethodsAPI } from '../../lib/api'
import Button from '../../components/ui/Button'
import Card from '../../components/ui/Card'
import StatCard from '../../components/StatCard'
import TransactionChart from '../../components/TransactionChart'
import { useAuthStore } from '../../stores/authStore'
import { format } from 'date-fns'

const DashboardPage = () => {
  const { user } = useAuthStore()

  // Fetch data (will use mock data in demo mode)
  const { data: stats } = useQuery('transaction-stats', transactionsAPI.getStatistics)
  const { data: bankAccounts } = useQuery('bank-accounts', bankAccountsAPI.getAll)
  const { data: paymentMethods } = useQuery('payment-methods', paymentMethodsAPI.getAll)
  const { data: transactions } = useQuery('recent-transactions', () => 
    transactionsAPI.getAll({ limit: 5 })
  )

  // Mock data for demo
  const mockStats = {
    total_amount_sent: 5420.50,
    total_transactions: 24,
    completed_transactions: 22,
    pending_transactions: 2,
    total_fees_paid: 125.30,
  }

  const displayStats = stats?.data || mockStats

  return (
    <div className="space-y-6">
      {/* Welcome Header - Animated */}
      <motion.div
        initial={{ opacity: 0, y: -20 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.5 }}
      >
        <h1 className="text-3xl font-bold text-[#566a7f]">
          Welcome back, {user?.first_name}! 👋
        </h1>
        <p className="text-[#a1acb8] mt-1">Here's what's happening with your transfers today.</p>
      </motion.div>

      {/* Quick Action Cards - Animated */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
        {[
          { to: '/send-money', icon: Send, color: 'primary', label: 'Send Money', desc: 'Quick Action' },
          { to: '/bank-accounts', icon: Wallet, color: 'success', label: 'Bank Accounts', desc: 'Manage' },
          { to: '/beneficiaries', icon: Users, color: 'info', label: 'Beneficiaries', desc: 'Recipients' }
        ].map((item, index) => {
          const Icon = item.icon
          const colorMap = {
            primary: 'from-primary-500 to-primary-700',
            success: 'from-success-500 to-success-700',
            info: 'from-info-500 to-info-700',
          }
          
          return (
            <Link key={index} to={item.to}>
              <motion.div
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ delay: index * 0.1 }}
                whileHover={{ scale: 1.03, y: -4 }}
                whileTap={{ scale: 0.98 }}
                className="bg-white rounded-lg p-6 shadow-sm border border-[#dbdade] hover:shadow-xl transition-all cursor-pointer group"
              >
                <div className="flex items-center justify-between">
                  <div className="flex items-center space-x-4">
                    <motion.div
                      whileHover={{ rotate: 360 }}
                      transition={{ duration: 0.6 }}
                      className={`p-3 bg-gradient-to-br ${colorMap[item.color]} rounded-xl shadow-lg`}
                    >
                      <Icon className="w-6 h-6 text-white" />
                    </motion.div>
                    <div>
                      <p className="text-sm text-[#a1acb8]">{item.desc}</p>
                      <p className="text-lg font-semibold text-[#566a7f] group-hover:text-primary-600 transition-colors">{item.label}</p>
                    </div>
                  </div>
                  <ArrowRight className="w-5 h-5 text-[#a1acb8] group-hover:text-primary-600 group-hover:translate-x-2 transition-all" />
                </div>
              </motion.div>
            </Link>
          )
        })}
      </div>

      {/* Statistics - Animated Cards */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <StatCard
          title="Total Sent"
          value={displayStats.total_amount_sent}
          icon={DollarSign}
          color="primary"
          prefix="$"
          decimals={2}
          trend="up"
          trendValue="12"
          delay={0}
        />
        <StatCard
          title="Transactions"
          value={displayStats.total_transactions}
          icon={Activity}
          color="success"
          trend="up"
          trendValue="8"
          delay={0.1}
        />
        <StatCard
          title="Bank Accounts"
          value={bankAccounts?.data?.length || 3}
          icon={Wallet}
          color="info"
          delay={0.2}
        />
        <StatCard
          title="Total Fees"
          value={displayStats.total_fees_paid}
          icon={TrendingUp}
          color="warning"
          prefix="$"
          decimals={2}
          delay={0.3}
        />
      </div>

      {/* Charts Section */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {/* Transaction Chart */}
        <motion.div
          initial={{ opacity: 0, x: -20 }}
          animate={{ opacity: 1, x: 0 }}
          transition={{ duration: 0.6, delay: 0.4 }}
        >
          <Card 
            title="Transaction Overview" 
            subtitle="Your transfer activity over time"
          >
            <TransactionChart type="area" />
          </Card>
        </motion.div>

        {/* Quick Stats */}
        <motion.div
          initial={{ opacity: 0, x: 20 }}
          animate={{ opacity: 1, x: 0 }}
          transition={{ duration: 0.6, delay: 0.5 }}
        >
          <Card title="Recent Activity" subtitle="Latest transfers">
            <div className="space-y-4">
              {[1, 2, 3, 4].map((_, index) => (
                <motion.div
                  key={index}
                  initial={{ opacity: 0, x: -20 }}
                  animate={{ opacity: 1, x: 0 }}
                  transition={{ delay: 0.6 + index * 0.1 }}
                  whileHover={{ x: 4 }}
                  className="flex items-center justify-between p-4 rounded-lg hover:bg-gray-50 transition-all cursor-pointer"
                >
                  <div className="flex items-center space-x-4">
                    <div className="w-10 h-10 bg-gradient-to-br from-primary-100 to-primary-200 rounded-full flex items-center justify-center">
                      <Send className="w-5 h-5 text-primary-600" />
                    </div>
                    <div>
                      <p className="font-medium text-[#566a7f]">Transfer to John Doe</p>
                      <p className="text-sm text-[#a1acb8]">2 hours ago</p>
                    </div>
                  </div>
                  <div className="text-right">
                    <p className="font-semibold text-[#566a7f]">$250.00</p>
                    <span className="inline-block px-2 py-0.5 text-xs rounded bg-success-100 text-success-700 font-medium">
                      Completed
                    </span>
                  </div>
                </motion.div>
              ))}
            </div>
            <div className="mt-6 text-center">
              <Link to="/transactions">
                <Button variant="outline" size="sm" className="group">
                  View All Transactions
                  <ArrowRight className="w-4 h-4 ml-2 group-hover:translate-x-2 transition-transform" />
                </Button>
              </Link>
            </div>
          </Card>
        </motion.div>
      </div>

      {/* Action Cards */}
      <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ delay: 0.8 }}
          whileHover={{ scale: 1.02 }}
        >
          <Link to="/send-money">
            <div className="relative overflow-hidden bg-gradient-to-br from-primary-500 to-primary-700 rounded-xl p-8 text-white shadow-xl hover:shadow-2xl transition-all cursor-pointer group">
              <div className="relative z-10">
                <Send className="w-12 h-12 mb-4 group-hover:scale-110 transition-transform" />
                <h3 className="text-2xl font-bold mb-2">Send Money Now</h3>
                <p className="text-primary-100 mb-4">Transfer funds globally in minutes</p>
                <div className="inline-flex items-center text-white font-semibold">
                  Get Started
                  <ArrowRight className="w-5 h-5 ml-2 group-hover:translate-x-2 transition-transform" />
                </div>
              </div>
              <div className="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
              <div className="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>
            </div>
          </Link>
        </motion.div>

        <motion.div
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ delay: 0.9 }}
          whileHover={{ scale: 1.02 }}
        >
          <Link to="/beneficiaries">
            <div className="relative overflow-hidden bg-gradient-to-br from-success-500 to-success-700 rounded-xl p-8 text-white shadow-xl hover:shadow-2xl transition-all cursor-pointer group">
              <div className="relative z-10">
                <Users className="w-12 h-12 mb-4 group-hover:scale-110 transition-transform" />
                <h3 className="text-2xl font-bold mb-2">Manage Recipients</h3>
                <p className="text-success-100 mb-4">Add and manage your beneficiaries</p>
                <div className="inline-flex items-center text-white font-semibold">
                  Manage
                  <ArrowRight className="w-5 h-5 ml-2 group-hover:translate-x-2 transition-transform" />
                </div>
              </div>
              <div className="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
              <div className="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>
            </div>
          </Link>
        </motion.div>
      </div>
    </div>
  )
}

const DollarSign = ({ className }) => <span className={className}>$</span>

export default DashboardPage
