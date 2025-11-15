<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@ghaseel.com',
            'phone' => '+966501234567',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create Regular Users
        $users = [
            [
                'name' => 'Ahmed Ali',
                'email' => 'ahmed@example.com',
                'phone' => '+966501111111',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Fatima Hassan',
                'email' => 'fatima@example.com',
                'phone' => '+966502222222',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Mohammed Saleh',
                'email' => 'mohammed@example.com',
                'phone' => '+966503333333',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Sara Abdullah',
                'email' => 'sara@example.com',
                'phone' => '+966504444444',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Khalid Ibrahim',
                'email' => 'khalid@example.com',
                'phone' => '+966505555555',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }

        $this->command->info('Users seeded successfully!');
        $this->command->info('Admin: admin@ghaseel.com / password123');
        $this->command->info('Regular users: Use any email above with password123');
    }
}
