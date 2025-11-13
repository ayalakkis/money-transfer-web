<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Agent;
use App\Models\Currency;
use App\Models\TransferService;
use App\Models\Promotion;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Roles
        $adminRole = Role::create(['name' => 'admin']);
        $agentRole = Role::create(['name' => 'agent']);
        $userRole = Role::create(['name' => 'user']);

        echo "✅ Roles created\n";

        // Create Admin User
        $admin = User::create([
            'id' => Str::uuid(),
            'email' => 'admin@moneytransfer.com',
            'password' => Hash::make('Admin@123'),
            'first_name' => 'System',
            'last_name' => 'Admin',
            'user_type' => 'admin',
            'is_verified' => true,
            'is_active' => true,
            'is_approved' => true,
        ]);
        $admin->assignRole('admin');

        echo "✅ Admin created: admin@moneytransfer.com / Admin@123\n";

        // Create Agent User
        $agentUser = User::create([
            'id' => Str::uuid(),
            'email' => 'agent@example.com',
            'password' => Hash::make('Agent@123'),
            'first_name' => 'John',
            'last_name' => 'Agent',
            'phone' => '+9647701234567',
            'country' => 'IQ',
            'user_type' => 'agent',
            'is_verified' => true,
            'is_active' => true,
            'is_approved' => true,
        ]);
        $agentUser->assignRole('agent');

        // Create Agent Profile
        Agent::create([
            'id' => Str::uuid(),
            'user_id' => $agentUser->id,
            'business_name' => 'Baghdad Exchange Center',
            'business_type' => 'exchange_office',
            'address' => 'Al-Mansour, Baghdad',
            'city' => 'Baghdad',
            'country' => 'IQ',
            'postal_code' => '10001',
            'latitude' => 33.3152,
            'longitude' => 44.3661,
            'working_hours' => [
                'sunday' => '09:00-18:00',
                'monday' => '09:00-18:00',
                'tuesday' => '09:00-18:00',
                'wednesday' => '09:00-18:00',
                'thursday' => '09:00-18:00',
                'friday' => 'Closed',
                'saturday' => '09:00-14:00',
            ],
            'commission_rate' => 1.5,
            'supported_currencies' => ['USD', 'EUR', 'IQD'],
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        echo "✅ Agent created: agent@example.com / Agent@123\n";

        // Create Regular User
        $user = User::create([
            'id' => Str::uuid(),
            'email' => 'aya@example.com',
            'password' => Hash::make('password123'),
            'first_name' => 'Aya',
            'last_name' => 'Ahmed',
            'phone' => '+9647709876543',
            'country' => 'US',
            'user_type' => 'user',
            'is_verified' => true,
            'is_active' => true,
            'is_approved' => true,
        ]);
        $user->assignRole('user');

        echo "✅ User created: aya@example.com / password123\n";

        // Seed Currencies
        $currencies = [
            ['code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$', 'flag_emoji' => '🇺🇸', 'is_popular' => true, 'sort_order' => 1],
            ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€', 'flag_emoji' => '🇪🇺', 'is_popular' => true, 'sort_order' => 2],
            ['code' => 'GBP', 'name' => 'British Pound', 'symbol' => '£', 'flag_emoji' => '🇬🇧', 'is_popular' => true, 'sort_order' => 3],
            ['code' => 'IQD', 'name' => 'Iraqi Dinar', 'symbol' => 'د.ع', 'flag_emoji' => '🇮🇶', 'is_popular' => true, 'sort_order' => 4],
            ['code' => 'AED', 'name' => 'UAE Dirham', 'symbol' => 'د.إ', 'flag_emoji' => '🇦🇪', 'is_popular' => false, 'sort_order' => 5],
            ['code' => 'SAR', 'name' => 'Saudi Riyal', 'symbol' => 'ر.س', 'flag_emoji' => '🇸🇦', 'is_popular' => false, 'sort_order' => 6],
        ];

        foreach ($currencies as $currency) {
            Currency::create(array_merge($currency, ['id' => Str::uuid()]));
        }

        echo "✅ Currencies seeded: 6 currencies\n";

        // Seed Transfer Services
        TransferService::create([
            'id' => Str::uuid(),
            'name' => 'US to Iraq - Express',
            'from_country' => 'US',
            'to_country' => 'IQ',
            'from_currency' => 'USD',
            'to_currency' => 'IQD',
            'bank_deposit' => true,
            'cash_pickup' => true,
            'mobile_wallet' => false,
            'speed' => 'same_day',
            'estimated_time' => '1-2 hours',
            'fixed_fee' => 2.99,
            'percentage_fee' => 0.0150, // 1.5%
            'min_fee' => 2.99,
            'max_fee' => 50.00,
            'min_amount' => 10.00,
            'max_amount' => 5000.00,
            'is_active' => true,
            'popularity_score' => 100,
        ]);

        echo "✅ Transfer services seeded\n";

        // Seed Promotion
        Promotion::create([
            'id' => Str::uuid(),
            'code' => 'WELCOME10',
            'title' => 'Welcome Bonus',
            'description' => '10% off your first transfer',
            'type' => 'percentage',
            'discount_percentage' => 10.00,
            'max_discount' => 50.00,
            'usage_limit' => 1000,
            'per_user_limit' => 1,
            'valid_until' => now()->addMonths(3),
            'is_active' => true,
        ]);

        echo "✅ Promotions seeded\n";

        echo "\n🎉 Database seeding completed!\n\n";
        echo "Login Credentials:\n";
        echo "Admin:  admin@moneytransfer.com / Admin@123\n";
        echo "Agent:  agent@example.com / Agent@123\n";
        echo "User:   aya@example.com / password123\n\n";
    }
}
