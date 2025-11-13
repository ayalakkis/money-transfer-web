import { useState } from 'react'
import { Link, useNavigate } from 'react-router-dom'
import { Mail, Lock, User, Phone, Eye, EyeOff, Check } from 'lucide-react'
import { motion } from 'framer-motion'
import toast from 'react-hot-toast'
import { useAuthStore } from '../../stores/authStore'
import Button from '../../components/ui/Button'
import Input from '../../components/ui/Input'

const RegisterPage = () => {
  const navigate = useNavigate()
  const { register, loading } = useAuthStore()
  const [showPassword, setShowPassword] = useState(false)
  const [showConfirmPassword, setShowConfirmPassword] = useState(false)
  const [formData, setFormData] = useState({
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
    password: '',
    password_confirmation: '',
  })

  const [passwordStrength, setPasswordStrength] = useState(0)

  const handleSubmit = async (e) => {
    e.preventDefault()
    
    if (formData.password !== formData.password_confirmation) {
      toast.error('Passwords do not match')
      return
    }
    
    const success = await register(formData)
    if (success) {
      navigate('/dashboard')
    }
  }

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value })
    
    // Calculate password strength
    if (e.target.name === 'password') {
      let strength = 0
      if (e.target.value.length >= 8) strength++
      if (/[a-z]/.test(e.target.value) && /[A-Z]/.test(e.target.value)) strength++
      if (/\d/.test(e.target.value)) strength++
      if (/[^a-zA-Z\d]/.test(e.target.value)) strength++
      setPasswordStrength(strength)
    }
  }

  const strengthColors = ['bg-danger-500', 'bg-warning-500', 'bg-info-500', 'bg-success-500']
  const strengthLabels = ['Weak', 'Fair', 'Good', 'Strong']

  return (
    <motion.div
      initial={{ opacity: 0, scale: 0.95 }}
      animate={{ opacity: 1, scale: 1 }}
      transition={{ duration: 0.5 }}
    >
      <div className="text-center mb-8">
        <motion.h2
          initial={{ opacity: 0, y: -20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ delay: 0.2 }}
          className="text-3xl font-bold text-[#566a7f]"
        >
          Create Account 🚀
        </motion.h2>
        <motion.p
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
          transition={{ delay: 0.3 }}
          className="mt-2 text-[#a1acb8]"
        >
          Start sending money worldwide today
        </motion.p>
      </div>

      <form onSubmit={handleSubmit} className="space-y-4">
        <div className="grid grid-cols-2 gap-4">
          <motion.div
            initial={{ opacity: 0, x: -20 }}
            animate={{ opacity: 1, x: 0 }}
            transition={{ delay: 0.4 }}
          >
            <Input
              label="First Name"
              type="text"
              name="first_name"
              value={formData.first_name}
              onChange={handleChange}
              icon={<User className="w-5 h-5" />}
              required
            />
          </motion.div>

          <motion.div
            initial={{ opacity: 0, x: 20 }}
            animate={{ opacity: 1, x: 0 }}
            transition={{ delay: 0.4 }}
          >
            <Input
              label="Last Name"
              type="text"
              name="last_name"
              value={formData.last_name}
              onChange={handleChange}
              icon={<User className="w-5 h-5" />}
              required
            />
          </motion.div>
        </div>

        <motion.div
          initial={{ opacity: 0, x: -20 }}
          animate={{ opacity: 1, x: 0 }}
          transition={{ delay: 0.5 }}
        >
          <Input
            label="Email Address"
            type="email"
            name="email"
            value={formData.email}
            onChange={handleChange}
            icon={<Mail className="w-5 h-5" />}
            placeholder="john@example.com"
            required
          />
        </motion.div>

        <motion.div
          initial={{ opacity: 0, x: -20 }}
          animate={{ opacity: 1, x: 0 }}
          transition={{ delay: 0.6 }}
        >
          <Input
            label="Phone Number"
            type="tel"
            name="phone"
            value={formData.phone}
            onChange={handleChange}
            icon={<Phone className="w-5 h-5" />}
            placeholder="+1234567890"
          />
        </motion.div>

        <motion.div
          initial={{ opacity: 0, x: -20 }}
          animate={{ opacity: 1, x: 0 }}
          transition={{ delay: 0.7 }}
          className="relative"
        >
          <Input
            label="Password"
            type={showPassword ? 'text' : 'password'}
            name="password"
            value={formData.password}
            onChange={handleChange}
            icon={<Lock className="w-5 h-5" />}
            required
          />
          <button
            type="button"
            onClick={() => setShowPassword(!showPassword)}
            className="absolute right-3 top-[38px] text-[#a1acb8] hover:text-[#566a7f] transition-colors"
          >
            {showPassword ? <EyeOff className="w-5 h-5" /> : <Eye className="w-5 h-5" />}
          </button>
          
          {/* Password Strength Indicator */}
          {formData.password && (
            <motion.div
              initial={{ opacity: 0, y: -10 }}
              animate={{ opacity: 1, y: 0 }}
              className="mt-2"
            >
              <div className="flex space-x-1 mb-1">
                {[0, 1, 2, 3].map((index) => (
                  <motion.div
                    key={index}
                    initial={{ scaleX: 0 }}
                    animate={{ scaleX: index < passwordStrength ? 1 : 0 }}
                    transition={{ duration: 0.3, delay: index * 0.1 }}
                    className={`h-1 flex-1 rounded-full ${
                      index < passwordStrength ? strengthColors[index] : 'bg-gray-200'
                    }`}
                  />
                ))}
              </div>
              <p className="text-xs text-[#a1acb8]">
                Strength: <span className={`font-medium ${
                  passwordStrength > 2 ? 'text-success-600' : 'text-warning-600'
                }`}>{strengthLabels[passwordStrength - 1]}</span>
              </p>
            </motion.div>
          )}
        </motion.div>

        <motion.div
          initial={{ opacity: 0, x: -20 }}
          animate={{ opacity: 1, x: 0 }}
          transition={{ delay: 0.8 }}
          className="relative"
        >
          <Input
            label="Confirm Password"
            type={showConfirmPassword ? 'text' : 'password'}
            name="password_confirmation"
            value={formData.password_confirmation}
            onChange={handleChange}
            icon={<Lock className="w-5 h-5" />}
            required
          />
          <button
            type="button"
            onClick={() => setShowConfirmPassword(!showConfirmPassword)}
            className="absolute right-3 top-[38px] text-[#a1acb8] hover:text-[#566a7f] transition-colors"
          >
            {showConfirmPassword ? <EyeOff className="w-5 h-5" /> : <Eye className="w-5 h-5" />}
          </button>
          
          {/* Match Indicator */}
          {formData.password && formData.password_confirmation && (
            <motion.div
              initial={{ opacity: 0, scale: 0.8 }}
              animate={{ opacity: 1, scale: 1 }}
              className="absolute right-3 top-[38px] mr-10"
            >
              {formData.password === formData.password_confirmation ? (
                <Check className="w-5 h-5 text-success-600" />
              ) : null}
            </motion.div>
          )}
        </motion.div>

        <motion.div
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
          transition={{ delay: 0.9 }}
          className="flex items-start"
        >
          <input
            id="terms"
            type="checkbox"
            required
            className="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded mt-1"
          />
          <label htmlFor="terms" className="ml-2 block text-sm text-[#697a8d]">
            I agree to the{' '}
            <a href="#" className="text-primary-600 hover:text-primary-700 font-medium">
              Terms of Service
            </a>{' '}
            and{' '}
            <a href="#" className="text-primary-600 hover:text-primary-700 font-medium">
              Privacy Policy
            </a>
          </label>
        </motion.div>

        <motion.div
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ delay: 1 }}
          whileHover={{ scale: 1.02 }}
          whileTap={{ scale: 0.98 }}
        >
          <Button
            type="submit"
            loading={loading}
            className="w-full shadow-lg hover:shadow-xl"
          >
            {loading ? 'Creating Account...' : 'Create Account'}
          </Button>
        </motion.div>
      </form>

      <motion.div
        initial={{ opacity: 0 }}
        animate={{ opacity: 1 }}
        transition={{ delay: 1.1 }}
        className="mt-6 text-center"
      >
        <p className="text-sm text-[#a1acb8]">
          Already have an account?{' '}
          <Link to="/login" className="text-primary-600 hover:text-primary-700 font-semibold">
            Sign in
          </Link>
        </p>
      </motion.div>
    </motion.div>
  )
}

export default RegisterPage
