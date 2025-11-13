import { motion } from 'framer-motion'
import AnimatedNumber from './AnimatedNumber'
import { TrendingUp, TrendingDown } from 'lucide-react'

const StatCard = ({ 
  title, 
  value, 
  icon: Icon, 
  trend, 
  trendValue, 
  color = 'primary',
  prefix = '',
  suffix = '',
  decimals = 0,
  delay = 0
}) => {
  const colorClasses = {
    primary: 'bg-gradient-to-br from-primary-500 to-primary-700',
    success: 'bg-gradient-to-br from-success-500 to-success-700',
    info: 'bg-gradient-to-br from-info-500 to-info-700',
    warning: 'bg-gradient-to-br from-warning-500 to-warning-700',
    danger: 'bg-gradient-to-br from-danger-500 to-danger-700',
  }

  return (
    <motion.div
      initial={{ opacity: 0, y: 20 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.5, delay }}
      whileHover={{ scale: 1.02, y: -4 }}
      className="bg-white rounded-lg p-6 shadow-sm border border-[#dbdade] hover:shadow-xl transition-all cursor-pointer"
    >
      <div className="flex items-center justify-between mb-4">
        <div className={`w-12 h-12 rounded-lg ${colorClasses[color]} flex items-center justify-center shadow-lg`}>
          <Icon className="w-6 h-6 text-white" />
        </div>
        {trend && (
          <div className={`flex items-center space-x-1 text-sm font-medium ${
            trend === 'up' ? 'text-success-600' : 'text-danger-600'
          }`}>
            {trend === 'up' ? <TrendingUp className="w-4 h-4" /> : <TrendingDown className="w-4 h-4" />}
            <span>{trendValue}%</span>
          </div>
        )}
      </div>
      <div className="space-y-1">
        <p className="text-sm text-[#a1acb8] font-medium">{title}</p>
        <p className="text-3xl font-bold text-[#566a7f]">
          <AnimatedNumber value={value} prefix={prefix} suffix={suffix} decimals={decimals} />
        </p>
      </div>
    </motion.div>
  )
}

export default StatCard

