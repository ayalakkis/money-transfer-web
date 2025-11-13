// TODO: Complete Payment Methods Management Page

import Card from '../../components/ui/Card'
import { CreditCard } from 'lucide-react'

const PaymentMethodsPage = () => {
  return (
    <div>
      <div className="mb-8">
        <h1 className="text-3xl font-bold text-gray-900">Payment Methods</h1>
        <p className="text-gray-600 mt-1">Manage cards and e-wallets</p>
      </div>

      <Card>
        <div className="text-center py-12">
          <CreditCard className="w-16 h-16 text-purple-600 mx-auto mb-4" />
          <h3 className="text-xl font-semibold mb-2">Payment Methods Page</h3>
          <p className="text-gray-600">
            This page should allow users to add cards (Stripe) and e-wallets
          </p>
        </div>
      </Card>
    </div>
  )
}

export default PaymentMethodsPage

