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

        // تحقق من العنوان
        $location = UserLocation::where('id', $data['location_id'])
            ->where('user_id', $user->id)
            ->firstOrFail();

        // تحقق من الخدمات
        $services = Service::whereIn('id', $data['service_ids'])->get();

        if ($services->isEmpty()) {
            abort(400, 'No valid services selected');
        }

        // إنشاء الطلب
        $order = Order::create([
            'user_id' => $user->id,
            'location_id' => $location->id,
            'delivery_type' => $data['delivery_type'],
            'pickup_date' => $data['pickup_date'],
            'pickup_time' => $data['pickup_time'],
            'delivery_date' => $data['delivery_date'],
            'delivery_time' => $data['delivery_time'],
            'notes' => $data['notes'] ?? null,
        ]);

        // ربط الخدمات
        $order->services()->sync($services->pluck('id'));

        return $order->load('services', 'location');
    }
}
