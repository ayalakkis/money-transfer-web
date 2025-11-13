import { Link } from 'react-router-dom'
import { Send, Shield, Zap, Globe, ArrowRight, Check, Star, Users, DollarSign, Clock } from 'lucide-react'
import { motion } from 'framer-motion'
import Button from '../components/ui/Button'
import AnimatedNumber from '../components/AnimatedNumber'

const HomePage = () => {
  const features = [
    {
      icon: Globe,
      title: 'Global Coverage',
      description: 'Send money to 80+ countries with multiple payout options',
      color: 'primary'
    },
    {
      icon: Zap,
      title: 'Instant Transfers',
      description: 'Money arrives in minutes, not days',
      color: 'warning'
    },
    {
      icon: Shield,
      title: 'Secure & Reliable',
      description: 'Bank-level security with 24/7 fraud monitoring',
      color: 'success'
    },
    {
      icon: DollarSign,
      title: 'Low Fees',
      description: 'Transparent pricing with no hidden charges',
      color: 'info'
    },
    {
      icon: Clock,
      title: 'Track in Real-Time',
      description: 'Monitor your transfer every step of the way',
      color: 'danger'
    },
    {
      icon: Users,
      title: 'Trusted by Millions',
      description: 'Join our community of satisfied customers',
      color: 'primary'
    },
  ]

  const stats = [
    { label: 'Countries', value: 80, suffix: '+' },
    { label: 'Users', value: 2.5, suffix: 'M+', decimals: 1 },
    { label: 'Transactions', value: 150, suffix: 'M+' },
    { label: 'Saved in Fees', value: 500, prefix: '$', suffix: 'M+' },
  ]

  return (
    <div>
      {/* Hero Section - More Dynamic */}
      <section className="relative overflow-hidden bg-gradient-to-br from-primary-600 via-primary-700 to-primary-900 text-white">
        {/* Animated background */}
        <div className="absolute inset-0 opacity-10">
          <div className="absolute top-20 left-10 w-72 h-72 bg-white rounded-full blur-3xl animate-pulse"></div>
          <div className="absolute bottom-20 right-10 w-96 h-96 bg-white rounded-full blur-3xl animate-pulse delay-1000"></div>
        </div>

        <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <motion.div
              initial={{ opacity: 0, x: -50 }}
              animate={{ opacity: 1, x: 0 }}
              transition={{ duration: 0.8 }}
            >
              <motion.div
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ delay: 0.2 }}
                className="inline-block mb-4"
              >
                <span className="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-sm font-medium">
                  ⚡ Send Money in Seconds
                </span>
              </motion.div>

              <h1 className="text-5xl lg:text-7xl font-bold mb-6 leading-tight">
                Send Money
                <span className="block bg-gradient-to-r from-yellow-300 to-orange-400 bg-clip-text text-transparent">
                  Worldwide
                </span>
              </h1>
              <p className="text-xl text-primary-100 mb-8 leading-relaxed">
                Transfer money to over 80 countries instantly. Low fees, great exchange rates, and trusted by millions.
              </p>

              {/* Stats Row */}
              <div className="grid grid-cols-4 gap-4 mb-8">
                {stats.map((stat, index) => (
                  <motion.div
                    key={index}
                    initial={{ opacity: 0, y: 20 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ delay: 0.4 + index * 0.1 }}
                    className="text-center"
                  >
                    <p className="text-2xl lg:text-3xl font-bold">
                      <AnimatedNumber value={stat.value} prefix={stat.prefix} suffix={stat.suffix} decimals={stat.decimals || 0} />
                    </p>
                    <p className="text-xs text-primary-200 mt-1">{stat.label}</p>
                  </motion.div>
                ))}
              </div>

              <motion.div
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ delay: 0.8 }}
                className="flex flex-col sm:flex-row gap-4"
              >
                <Link to="/register">
                  <Button size="lg" className="bg-white text-primary-700 hover:bg-gray-100 w-full sm:w-auto shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all">
                    Get Started Free
                    <ArrowRight className="w-5 h-5 ml-2" />
                  </Button>
                </Link>
                <Link to="/login">
                  <Button size="lg" variant="outline" className="border-2 border-white text-white hover:bg-white hover:text-primary-700 w-full sm:w-auto">
                    Sign In
                  </Button>
                </Link>
              </motion.div>
            </motion.div>

            {/* Animated Card - Currency Converter Preview */}
            <motion.div
              initial={{ opacity: 0, x: 50 }}
              animate={{ opacity: 1, x: 0 }}
              transition={{ duration: 0.8, delay: 0.3 }}
              className="hidden lg:block"
            >
              <motion.div
                animate={{ y: [0, -10, 0] }}
                transition={{ duration: 4, repeat: Infinity, ease: "easeInOut" }}
                className="bg-white/10 backdrop-blur-xl rounded-2xl p-8 border border-white/20 shadow-2xl"
              >
                <div className="space-y-6">
                  <div className="flex items-center justify-between text-white">
                    <span className="text-sm opacity-75">You send</span>
                    <span className="text-2xl font-bold">$100.00</span>
                  </div>
                  <div className="border-t border-white/20"></div>
                  <div className="flex items-center justify-center">
                    <motion.div
                      animate={{ rotate: 360 }}
                      transition={{ duration: 2, repeat: Infinity, ease: "linear" }}
                      className="w-10 h-10 border-2 border-white/30 border-t-white rounded-full"
                    ></motion.div>
                  </div>
                  <div className="border-t border-white/20"></div>
                  <div className="flex items-center justify-between text-white">
                    <span className="text-sm opacity-75">Recipient gets</span>
                    <span className="text-2xl font-bold">£79.35</span>
                  </div>
                  <div className="flex items-center justify-between text-sm text-white/75">
                    <span>Fee: $2.99</span>
                    <span>Rate: 1 USD = 0.7935 GBP</span>
                  </div>
                  <motion.button
                    whileHover={{ scale: 1.05 }}
                    whileTap={{ scale: 0.95 }}
                    className="w-full bg-white text-primary-700 font-semibold py-3 rounded-lg shadow-lg hover:shadow-xl transition-all"
                  >
                    Continue
                  </motion.button>
                </div>
              </motion.div>
            </motion.div>
          </div>
        </div>

        {/* Floating elements */}
        <motion.div
          animate={{ y: [0, 20, 0], rotate: [0, 5, 0] }}
          transition={{ duration: 5, repeat: Infinity }}
          className="absolute top-40 right-20 w-20 h-20 bg-yellow-400 rounded-full opacity-20 blur-xl"
        ></motion.div>
      </section>

      {/* Features Section - More Interactive */}
      <section className="py-20 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            className="text-center mb-16"
          >
            <h2 className="text-4xl lg:text-5xl font-bold text-[#566a7f] mb-4">
              Why Choose Us?
            </h2>
            <p className="text-lg text-[#a1acb8] max-w-2xl mx-auto">
              Experience the future of money transfers with our cutting-edge platform
            </p>
          </motion.div>

          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {features.map((feature, index) => {
              const Icon = feature.icon
              const colorClasses = {
                primary: 'bg-primary-100 text-primary-600',
                success: 'bg-success-100 text-success-600',
                info: 'bg-info-100 text-info-600',
                warning: 'bg-warning-100 text-warning-600',
                danger: 'bg-danger-100 text-danger-600',
              }

              return (
                <motion.div
                  key={index}
                  initial={{ opacity: 0, y: 30 }}
                  whileInView={{ opacity: 1, y: 0 }}
                  viewport={{ once: true }}
                  transition={{ delay: index * 0.1 }}
                  whileHover={{ y: -8, scale: 1.02 }}
                  className="bg-white p-6 rounded-xl shadow-sm border border-[#dbdade] hover:shadow-xl transition-all cursor-pointer group"
                >
                  <motion.div
                    whileHover={{ rotate: 360, scale: 1.1 }}
                    transition={{ duration: 0.6 }}
                    className={`w-16 h-16 ${colorClasses[feature.color]} rounded-xl flex items-center justify-center mx-auto mb-6 group-hover:shadow-lg transition-shadow`}
                  >
                    <Icon className="w-8 h-8" />
                  </motion.div>
                  <h3 className="text-xl font-semibold text-[#566a7f] mb-3 text-center">{feature.title}</h3>
                  <p className="text-[#a1acb8] text-center leading-relaxed">
                    {feature.description}
                  </p>
                </motion.div>
              )
            })}
          </div>
        </div>
      </section>

      {/* How It Works - Animated Steps */}
      <section className="py-20 bg-gradient-to-br from-gray-50 to-primary-50/30">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            className="text-center mb-16"
          >
            <h2 className="text-4xl lg:text-5xl font-bold text-[#566a7f] mb-4">
              How It Works
            </h2>
            <p className="text-lg text-[#a1acb8]">
              Send money in just 3 simple steps
            </p>
          </motion.div>

          <div className="relative">
            {/* Connection Line */}
            <div className="hidden lg:block absolute top-1/2 left-0 right-0 h-1 bg-gradient-to-r from-primary-200 via-primary-400 to-primary-600 transform -translate-y-1/2 z-0"></div>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-8 relative z-10">
              {[
                {
                  step: '1',
                  title: 'Enter Amount',
                  description: 'Choose amount, currencies, and see live exchange rates',
                  icon: DollarSign
                },
                {
                  step: '2',
                  title: 'Choose Recipient',
                  description: 'Select from saved beneficiaries or add new',
                  icon: Users
                },
                {
                  step: '3',
                  title: 'Send & Track',
                  description: 'Pay securely and track your transfer in real-time',
                  icon: Send
                }
              ].map((item, index) => {
                const Icon = item.icon
                return (
                  <motion.div
                    key={index}
                    initial={{ opacity: 0, scale: 0.8 }}
                    whileInView={{ opacity: 1, scale: 1 }}
                    viewport={{ once: true }}
                    transition={{ delay: index * 0.2, duration: 0.5 }}
                    className="relative"
                  >
                    <div className="bg-white rounded-2xl p-8 shadow-lg border border-[#dbdade] hover:shadow-2xl transition-all">
                      <motion.div
                        whileHover={{ scale: 1.1, rotate: 5 }}
                        className="w-20 h-20 bg-gradient-to-br from-primary-500 to-primary-700 text-white rounded-2xl flex items-center justify-center text-3xl font-bold mb-6 mx-auto shadow-xl relative"
                      >
                        <div className="absolute inset-0 bg-white opacity-0 hover:opacity-20 rounded-2xl transition-opacity"></div>
                        {item.step}
                      </motion.div>
                      <div className="mb-4 flex justify-center">
                        <Icon className="w-8 h-8 text-primary-600" />
                      </div>
                      <h3 className="text-xl font-semibold text-[#566a7f] mb-3 text-center">{item.title}</h3>
                      <p className="text-[#a1acb8] text-center leading-relaxed">
                        {item.description}
                      </p>
                    </div>
                  </motion.div>
                )
              })}
            </div>
          </div>
        </div>
      </section>

      {/* Trust Indicators */}
      <section className="py-20 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            <motion.div
              initial={{ opacity: 0, y: 20 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              className="text-center"
            >
              <motion.div
                animate={{ scale: [1, 1.1, 1] }}
                transition={{ duration: 2, repeat: Infinity }}
                className="w-20 h-20 bg-gradient-to-br from-success-400 to-success-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg"
              >
                <Check className="w-10 h-10 text-white" />
              </motion.div>
              <h3 className="text-2xl font-bold text-[#566a7f] mb-2">Trusted & Secure</h3>
              <p className="text-[#a1acb8]">Regulated and licensed in multiple countries</p>
            </motion.div>

            <motion.div
              initial={{ opacity: 0, y: 20 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ delay: 0.2 }}
              className="text-center"
            >
              <motion.div
                animate={{ rotate: [0, 360] }}
                transition={{ duration: 3, repeat: Infinity, ease: "linear" }}
                className="w-20 h-20 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg"
              >
                <Star className="w-10 h-10 text-white" />
              </motion.div>
              <h3 className="text-2xl font-bold text-[#566a7f] mb-2">4.8/5 Rating</h3>
              <p className="text-[#a1acb8]">Based on 50,000+ customer reviews</p>
            </motion.div>

            <motion.div
              initial={{ opacity: 0, y: 20 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ delay: 0.4 }}
              className="text-center"
            >
              <motion.div
                animate={{ y: [0, -10, 0] }}
                transition={{ duration: 2, repeat: Infinity }}
                className="w-20 h-20 bg-gradient-to-br from-info-400 to-info-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg"
              >
                <Shield className="w-10 h-10 text-white" />
              </motion.div>
              <h3 className="text-2xl font-bold text-[#566a7f] mb-2">Bank-Level Security</h3>
              <p className="text-[#a1acb8]">Your money is always protected</p>
            </motion.div>
          </div>
        </div>
      </section>

      {/* CTA Section - More Dynamic */}
      <section className="relative py-24 bg-gradient-to-r from-primary-600 to-primary-800 text-white overflow-hidden">
        {/* Animated background patterns */}
        <motion.div
          animate={{ rotate: 360 }}
          transition={{ duration: 20, repeat: Infinity, ease: "linear" }}
          className="absolute -top-20 -left-20 w-64 h-64 bg-white/10 rounded-full blur-3xl"
        ></motion.div>
        <motion.div
          animate={{ rotate: -360 }}
          transition={{ duration: 25, repeat: Infinity, ease: "linear" }}
          className="absolute -bottom-20 -right-20 w-96 h-96 bg-white/10 rounded-full blur-3xl"
        ></motion.div>

        <div className="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <motion.div
            initial={{ opacity: 0, scale: 0.9 }}
            whileInView={{ opacity: 1, scale: 1 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6 }}
          >
            <h2 className="text-4xl lg:text-5xl font-bold mb-6">
              Ready to Send Money?
            </h2>
            <p className="text-xl text-primary-100 mb-10 leading-relaxed">
              Join millions of people who trust us with their money transfers. 
              Get started in less than 2 minutes!
            </p>
            <Link to="/register">
              <motion.div
                whileHover={{ scale: 1.05 }}
                whileTap={{ scale: 0.95 }}
              >
                <Button size="lg" className="bg-white text-primary-700 hover:bg-gray-100 shadow-2xl text-xl px-10 py-4">
                  Create Free Account
                  <ArrowRight className="w-6 h-6 ml-2" />
                </Button>
              </motion.div>
            </Link>
            <p className="mt-4 text-sm text-primary-200">
              No credit card required • Setup in 2 minutes • Cancel anytime
            </p>
          </motion.div>
        </div>
      </section>
    </div>
  )
}

export default HomePage
