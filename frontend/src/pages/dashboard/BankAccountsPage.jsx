// TODO: Complete Bank Accounts Management Page

import Card from '../../components/ui/Card'
import { Wallet } from 'lucide-react'

const BankAccountsPage = () => {
  return (
    <div>
      <div className="mb-8">
        <h1 className="text-3xl font-bold text-gray-900">Bank Accounts</h1>
        <p className="text-gray-600 mt-1">Manage your bank accounts</p>
      </div>

      <Card>
        <div className="text-center py-12">
          <Wallet className="w-16 h-16 text-green-600 mx-auto mb-4" />
          <h3 className="text-xl font-semibold mb-2">Bank Accounts Page</h3>
          <p className="text-gray-600">
            This page should allow users to add, verify, and manage bank accounts
          </p>
        </div>
      </Card>
    </div>
  )
}

export default BankAccountsPage

