// TODO: Complete Beneficiaries Management Page

import Card from '../../components/ui/Card'
import { Users } from 'lucide-react'

const BeneficiariesPage = () => {
  return (
    <div>
      <div className="mb-8">
        <h1 className="text-3xl font-bold text-gray-900">Beneficiaries</h1>
        <p className="text-gray-600 mt-1">Manage recipients</p>
      </div>

      <Card>
        <div className="text-center py-12">
          <Users className="w-16 h-16 text-indigo-600 mx-auto mb-4" />
          <h3 className="text-xl font-semibold mb-2">Beneficiaries Page</h3>
          <p className="text-gray-600">
            This page should allow users to add and manage recipients
          </p>
        </div>
      </Card>
    </div>
  )
}

export default BeneficiariesPage

