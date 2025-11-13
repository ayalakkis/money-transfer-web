import { motion } from 'framer-motion'
import { LineChart, Line, AreaChart, Area, BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer, Legend } from 'recharts'

const TransactionChart = ({ type = 'area' }) => {
  // Mock data - replace with real data from API
  const data = [
    { month: 'Jan', transactions: 45, amount: 4500 },
    { month: 'Feb', transactions: 52, amount: 5200 },
    { month: 'Mar', transactions: 61, amount: 6100 },
    { month: 'Apr', transactions: 58, amount: 5800 },
    { month: 'May', transactions: 75, amount: 7500 },
    { month: 'Jun', transactions: 88, amount: 8800 },
  ]

  const CustomTooltip = ({ active, payload }) => {
    if (active && payload && payload.length) {
      return (
        <div className="bg-white p-3 rounded-lg shadow-lg border border-[#dbdade]">
          <p className="text-sm font-semibold text-[#566a7f]">{payload[0].payload.month}</p>
          <p className="text-xs text-primary-600">
            Transactions: {payload[0].value}
          </p>
          {payload[1] && (
            <p className="text-xs text-success-600">
              Amount: ${payload[1].value}
            </p>
          )}
        </div>
      )
    }
    return null
  }

  return (
    <motion.div
      initial={{ opacity: 0 }}
      animate={{ opacity: 1 }}
      transition={{ duration: 0.8 }}
      className="w-full h-[300px]"
    >
      <ResponsiveContainer width="100%" height="100%">
        {type === 'area' ? (
          <AreaChart data={data}>
            <defs>
              <linearGradient id="colorTransactions" x1="0" y1="0" x2="0" y2="1">
                <stop offset="5%" stopColor="#7c3aed" stopOpacity={0.3}/>
                <stop offset="95%" stopColor="#7c3aed" stopOpacity={0}/>
              </linearGradient>
            </defs>
            <CartesianGrid strokeDasharray="3 3" stroke="#f0f0f0" />
            <XAxis dataKey="month" stroke="#a1acb8" style={{ fontSize: '12px' }} />
            <YAxis stroke="#a1acb8" style={{ fontSize: '12px' }} />
            <Tooltip content={<CustomTooltip />} />
            <Area 
              type="monotone" 
              dataKey="transactions" 
              stroke="#7c3aed" 
              strokeWidth={3}
              fill="url(#colorTransactions)" 
            />
          </AreaChart>
        ) : type === 'bar' ? (
          <BarChart data={data}>
            <CartesianGrid strokeDasharray="3 3" stroke="#f0f0f0" />
            <XAxis dataKey="month" stroke="#a1acb8" style={{ fontSize: '12px' }} />
            <YAxis stroke="#a1acb8" style={{ fontSize: '12px' }} />
            <Tooltip content={<CustomTooltip />} />
            <Bar dataKey="transactions" fill="#7c3aed" radius={[8, 8, 0, 0]} />
          </BarChart>
        ) : (
          <LineChart data={data}>
            <CartesianGrid strokeDasharray="3 3" stroke="#f0f0f0" />
            <XAxis dataKey="month" stroke="#a1acb8" style={{ fontSize: '12px' }} />
            <YAxis stroke="#a1acb8" style={{ fontSize: '12px' }} />
            <Tooltip content={<CustomTooltip />} />
            <Legend />
            <Line 
              type="monotone" 
              dataKey="transactions" 
              stroke="#7c3aed" 
              strokeWidth={3}
              dot={{ fill: '#7c3aed', r: 5 }}
              activeDot={{ r: 8 }}
            />
            <Line 
              type="monotone" 
              dataKey="amount" 
              stroke="#22c55e" 
              strokeWidth={3}
              dot={{ fill: '#22c55e', r: 5 }}
            />
          </LineChart>
        )}
      </ResponsiveContainer>
    </motion.div>
  )
}

export default TransactionChart

