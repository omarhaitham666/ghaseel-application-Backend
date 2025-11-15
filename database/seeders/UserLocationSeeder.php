<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserLocation;
use Illuminate\Database\Seeder;

class UserLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 'user')->get();

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please run UserSeeder first.');
            return;
        }

        $locations = [
            [
                'name' => 'المنزل',
                'street_address' => 'شارع الملك فهد',
                'building_number' => '1234',
                'apartment' => '101',
                'city' => 'الرياض',
                'state' => 'منطقة الرياض',
                'postal_code' => '12345',
                'country' => 'السعودية',
                'latitude' => 24.7136,
                'longitude' => 46.6753,
                'phone' => '+966501111111',
            ],
            [
                'name' => 'العمل',
                'street_address' => 'طريق الملك عبدالعزيز',
                'building_number' => '5678',
                'apartment' => '302',
                'city' => 'الرياض',
                'state' => 'منطقة الرياض',
                'postal_code' => '12346',
                'country' => 'السعودية',
                'latitude' => 24.7136,
                'longitude' => 46.6753,
                'phone' => '+966501111111',
            ],
            [
                'name' => 'المنزل',
                'street_address' => 'شارع التحلية',
                'building_number' => '9012',
                'apartment' => '205',
                'city' => 'جدة',
                'state' => 'منطقة مكة المكرمة',
                'postal_code' => '21421',
                'country' => 'السعودية',
                'latitude' => 21.4858,
                'longitude' => 39.1925,
                'phone' => '+966502222222',
            ],
            [
                'name' => 'المنزل',
                'street_address' => 'شارع العليا',
                'building_number' => '3456',
                'apartment' => '401',
                'city' => 'الدمام',
                'state' => 'المنطقة الشرقية',
                'postal_code' => '32245',
                'country' => 'السعودية',
                'latitude' => 26.4207,
                'longitude' => 50.0888,
                'phone' => '+966503333333',
            ],
        ];

        foreach ($users as $index => $user) {
            // Each user gets at least one location
            $locationData = $locations[$index % count($locations)];
            $locationData['user_id'] = $user->id;
            UserLocation::create($locationData);

            // First two users get an additional location
            if ($index < 2 && $index + 1 < count($locations)) {
                $secondLocation = $locations[$index + 1];
                $secondLocation['user_id'] = $user->id;
                // Make it unique by changing the name
                $secondLocation['name'] = 'العمل';
                UserLocation::create($secondLocation);
            }
        }

        $this->command->info('User locations seeded successfully!');
    }
}
