<?php
namespace App\Services;

use App\Models\Order;
use App\Models\Service;
use App\Models\UserLocation;
use Illuminate\Support\Facades\Auth;

class OrderService
{
    public function createOrder(array $data)
    {
        $user = Auth::user();

        $location = UserLocation::where('id', $data['location_id'])
            ->where('user_id', $user->id)
            ->firstOrFail();

        $services = Service::whereIn('id', $data['service_ids'])->get();

        if ($services->isEmpty()) {
            abort(400, 'No valid services selected');
        }

        $order = Order::create([
            'user_id' => $user->id,
            'location_id' => $location->id,
            'delivery_type' => $data['delivery_type'],
            'pickup_date' => $data['pickup_date'],
            'pickup_time' => $data['pickup_time'],
            'delivery_date' => $data['delivery_date'],
            'delivery_time' => $data['delivery_time'],
            'notes' => $data['notes'] ?? null,
            'admin_status' => 'pending',
            'order_status' => null
        ]);

        $order->services()->sync($services->pluck('id'));

        return $order->load(['user', 'services', 'location']);
    }


    /**
     * Admin accept order.
     *
     * @param Order $order
     * @param float $finalPrice
     * @return Order
     */
    public function adminAccept(Order $order, float $finalPrice)
    {
        $order->update([
            'admin_status' => 'accepted',
            'order_status' => 'processing',
            'final_price' => $finalPrice,
            'rejection_reason' => null,
        ]);

        \App\Models\Cart::updateOrCreate(
            ['order_id' => $order->id],
            [
                'user_id' => $order->user_id,
                'admin_status' => $order->admin_status,
                'order_status' => $order->order_status,
                'price' => $order->final_price,
                'rejection_reason' => null,
            ]
        );

        return $order->load(['user', 'services', 'location']);
    }

    /**
     * Admin reject order.
     *
     * @param Order $order
     * @param string $reason
     * @return Order
     */
    public function adminReject(Order $order, string $reason)
    {
        $order->update([
            'admin_status' => 'rejected',
            'order_status' => null,
            'final_price' => null,
            'rejection_reason' => $reason,
        ]);

        \App\Models\Cart::updateOrCreate(
            ['order_id' => $order->id],
            [
                'user_id' => $order->user_id,
                'admin_status' => $order->admin_status,
                'order_status' => $order->order_status,
                'final_price' => $order->final_price,
                'rejection_reason' => $reason,
            ]
        );

        return $order->load(['user', 'services', 'location']);
    }



    public function updateOrderStatus(Order $order, $status)
    {
        $order->update([
            'order_status' => $status
        ]);

        return $order->load(['user', 'services', 'location']);
    }

    /**
     * Get all orders with relationships.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllOrders()
    {
        return Order::with(['user', 'services', 'location'])->latest()->get();
    }

    /**
     * Get order details with relationships.
     *
     * @param Order $order
     * @return Order
     */
    public function getOrderDetails(Order $order)
    {
        return $order->load(['user', 'services', 'location']);
    }

    /**
     * Get dashboard statistics.
     *
     * @return array
     */
    public function getDashboardStatistics()
    {
        return [
            'total_orders' => Order::count(),
            'admin_pending' => Order::where('admin_status', 'pending')->count(),
            'admin_accepted' => Order::where('admin_status', 'accepted')->count(),
            'admin_rejected' => Order::where('admin_status', 'rejected')->count(),
            'processing_orders' => Order::where('order_status', 'processing')->count(),
            'completed_orders' => Order::where('order_status', 'completed')->count(),
            'delivered_orders' => Order::where('order_status', 'delivered')->count(),
            'total_revenue' => Order::where('order_status', 'delivered')->sum('final_price'),
        ];
    }

    /**
     * Delete an order.
     *
     * @param int $orderId
     * @return bool
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function deleteOrder(int $orderId)
    {
        $order = Order::findOrFail($orderId);
        return $order->delete();
    }

    /**
     * Get user's orders with relationships.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserOrders($user)
    {
        return Order::with(['services', 'location'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();
    }

    /**
     * Get user's order details.
     *
     * @param \App\Models\User $user
     * @param Order $order
     * @return Order
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getUserOrderDetails($user, Order $order)
    {
        // Ensure the order belongs to the user
        if ($order->user_id !== $user->id) {
            abort(403, 'Unauthorized access to this order');
        }

        return $order->load(['services', 'location', 'user']);
    }
}
