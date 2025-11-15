<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(private OrderService $service) {}

    public function store(Request $request)
    {
        $data = $request->validate([
            'service_ids' => 'required|array',
            'service_ids.*' => 'integer|exists:services,id',
            'location_id' => 'required|integer|exists:user_locations,id',
            'delivery_type' => 'required|string',
            'pickup_date' => 'required|date',
            'pickup_time' => 'required',
            'delivery_date' => 'required|date',
            'delivery_time' => 'required',
            'notes' => 'nullable|string',
        ]);

        return response()->json($this->service->createOrder($data), 201);
    }

    public function index(Request $request)
{
    $user = $request->user();
    $orders = Order::with('services', 'location')
                   ->where('user_id', $user->id)
                   ->get();

    return response()->json([
        'status' => 'success',
        'data' => $orders,
    ]);
}

}
