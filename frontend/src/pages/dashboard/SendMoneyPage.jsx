// TODO: Complete Send Money Page
// This is the main feature - allows users to send money internationally

import { useState } from 'react'
import Card from '../../components/ui/Card'
import Button from '../../components/ui/Button'
import Input from '../../components/ui/Input'
import { Send, ArrowRight } from 'lucide-react'

const SendMoneyPage = () => {
  const [step, setStep] = useState(1) // 1: Amount, 2: Recipient, 3: Payment, 4: Review

  return (
    <div className="max-w-4xl mx-auto">
      <div className="mb-8">
        <h1 className="text-3xl font-bold text-gray-900">Send Money</h1>
        <p className="text-gray-600 mt-1">Transfer money worldwide</p>
      </div>

      <Card>
        <div className="text-center py-12">
          <Send className="w-16 h-16 text-primary-600 mx-auto mb-4" />
          <h3 className="text-xl font-semibold mb-2">Send Money Feature</h3>
          <p className="text-gray-600 mb-6">
            This page needs to be completed. It should include:
          </p>
          <ul className="text-left max-w-md mx-auto space-y-2 text-gray-700 mb-8">
            <li>• Step 1: Enter amount and select currencies</li>
            <li>• Step 2: Select or add beneficiary</li>
            <li>• Step 3: Choose payment method</li>
            <li>• Step 4: Review and confirm transfer</li>
            <li>• Show exchange rates and fees</li>
            <li>• Calculate recipient amount</li>
          </ul>
          <p className="text-sm text-gray-500">
            See FRONTEND_README.md for implementation details
          </p>
        </div>
      </Card>
    </div>
  )
}

export default SendMoneyPage

