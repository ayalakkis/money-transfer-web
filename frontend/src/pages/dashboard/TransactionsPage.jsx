// TODO: Complete Transactions History Page

import Card from '../../components/ui/Card'
import { History } from 'lucide-react'

const TransactionsPage = () => {
  return (
    <div>
      <div className="mb-8">
        <h1 className="text-3xl font-bold text-gray-900">Transaction History</h1>
        <p className="text-gray-600 mt-1">View all your money transfers</p>
      </div>

      <Card>
        <div className="text-center py-12">
          <History className="w-16 h-16 text-blue-600 mx-auto mb-4" />
          <h3 className="text-xl font-semibold mb-2">Transactions Page</h3>
          <p className="text-gray-600">
            This page should display transaction history with filters, search, and pagination
          </p>
        </div>
      </Card>
    </div>
  )
}

export default TransactionsPage

