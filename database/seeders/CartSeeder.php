<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get accepted orders that don't have a cart yet
        $acceptedOrders = Order::where('admin_status', 'accepted')
            ->whereDoesntHave('cart')
            ->get();

        if ($acceptedOrders->isEmpty()) {
            $this->command->warn('No accepted orders found. Please run OrderSeeder first.');
            return;
        }

        foreach ($acceptedOrders as $order) {
            Cart::create([
                'user_id' => $order->user_id,
                'order_id' => $order->id,
                'price' => $order->final_price,
                'admin_status' => $order->admin_status,
                'order_status' => $order->order_status,
                'rejection_reason' => null,
            ]);
        }

        // Also create some carts for rejected orders
        $rejectedOrders = Order::where('admin_status', 'rejected')
            ->whereDoesntHave('cart')
            ->take(3)
            ->get();

        foreach ($rejectedOrders as $order) {
            Cart::create([
                'user_id' => $order->user_id,
                'order_id' => $order->id,
                'price' => null,
                'admin_status' => $order->admin_status,
                'order_status' => null,
                'rejection_reason' => $order->rejection_reason,
            ]);
        }

        $this->command->info('Cart items seeded successfully!');
    }
}
