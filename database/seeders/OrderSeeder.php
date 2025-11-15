<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use App\Models\UserLocation;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $services = Service::all();
        $adminStatuses = ['pending', 'accepted', 'rejected'];
        $orderStatuses = ['processing', 'completed', 'delivered'];

        if ($users->isEmpty() || $services->isEmpty()) {
            $this->command->warn('Users or Services not found. Please run UserSeeder and ServiceSeeder first.');
            return;
        }

        foreach ($users as $user) {
            $locations = $user->locations;
            
            if ($locations->isEmpty()) {
                continue;
            }

            // Create 2-4 orders per user
            $orderCount = rand(2, 4);

            for ($i = 0; $i < $orderCount; $i++) {
                $location = $locations->random();
                $pickupDate = Carbon::now()->addDays(rand(1, 30));
                $deliveryDate = $pickupDate->copy()->addDays(rand(1, 3));

                $adminStatus = $adminStatuses[array_rand($adminStatuses)];
                $orderStatus = null;
                $finalPrice = null;
                $rejectionReason = null;

                if ($adminStatus === 'accepted') {
                    $orderStatus = $orderStatuses[array_rand($orderStatuses)];
                    $finalPrice = rand(100, 500) + (rand(0, 99) / 100);
                } elseif ($adminStatus === 'rejected') {
                    $rejectionReason = 'الخدمة غير متوفرة في منطقتك';
                }

                $order = Order::create([
                    'user_id' => $user->id,
                    'location_id' => $location->id,
                    'delivery_type' => rand(0, 1) ? 'normal' : 'express',
                    'pickup_date' => $pickupDate->format('Y-m-d'),
                    'pickup_time' => sprintf('%02d:00', rand(8, 18)),
                    'delivery_date' => $deliveryDate->format('Y-m-d'),
                    'delivery_time' => sprintf('%02d:00', rand(10, 20)),
                    'notes' => rand(0, 1) ? 'يرجى الحضور في الوقت المحدد' : null,
                    'admin_status' => $adminStatus,
                    'order_status' => $orderStatus,
                    'final_price' => $finalPrice,
                    'rejection_reason' => $rejectionReason,
                ]);

                // Attach 1-3 random services to each order
                $orderServices = $services->random(rand(1, min(3, $services->count())));
                $order->services()->attach($orderServices->pluck('id'));
            }
        }

        $this->command->info('Orders seeded successfully!');
    }
}
