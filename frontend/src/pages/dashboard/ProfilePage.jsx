// TODO: Complete Profile Management Page

import Card from '../../components/ui/Card'
import { User } from 'lucide-react'

const ProfilePage = () => {
  return (
    <div>
      <div className="mb-8">
        <h1 className="text-3xl font-bold text-gray-900">Profile Settings</h1>
        <p className="text-gray-600 mt-1">Manage your account information</p>
      </div>

      <Card>
        <div className="text-center py-12">
          <User className="w-16 h-16 text-gray-600 mx-auto mb-4" />
          <h3 className="text-xl font-semibold mb-2">Profile Page</h3>
          <p className="text-gray-600">
            This page should allow users to edit their profile and settings
          </p>
        </div>
      </Card>
    </div>
  )
}

export default ProfilePage

