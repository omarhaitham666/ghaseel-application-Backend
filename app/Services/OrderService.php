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

        return $order->load('services', 'location');
    }


  public function adminAccept(Order $order, float $finalPrice)
{
    // تحديث الـOrder
    $order->update([
        'admin_status' => 'accepted',
        'order_status' => 'processing',
        'final_price' => $finalPrice,
        'rejection_reason' => null,
    ]);

    // إضافة الأوردر في الـCart
    \App\Models\Cart::updateOrCreate(
        ['order_id' => $order->id], // لو موجود بالفعل حدثه
        [
            'user_id' => $order->user_id,
            'admin_status' => $order->admin_status,
            'order_status' => $order->order_status,
            'price' => $order->final_price,
            'rejection_reason' => null,
        ]
    );

    // ترجع الـOrder كامل مع البيانات
    return $order->load('location', 'services');
}

public function adminReject(Order $order, string $reason)
{
    // تحديث الـOrder
    $order->update([
        'admin_status' => 'rejected',
        'order_status' => null,
        'final_price' => null,
        'rejection_reason' => $reason,
    ]);

    // إضافة الأوردر في الـCart
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

    // ترجع الـOrder كامل مع البيانات
    return $order->load('location', 'services');
}



    public function updateOrderStatus(Order $order, $status)
    {
        $order->update([
            'order_status' => $status
        ]);

        return $order;
    }
}
