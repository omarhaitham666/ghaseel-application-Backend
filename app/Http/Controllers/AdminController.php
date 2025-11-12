<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateOrderStatusRequest;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Get all orders (admin only).
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllOrders(Request $request)
    {
        $orders = $this->orderService->getAllOrders();

        return response()->json([
            'status' => 'success',
            'data' => $orders,
        ]);
    }

    /**
     * Get order details (admin only).
     *
     * @param Request $request
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrder(Request $request, Order $order)
    {
        $order->load(['user', 'orderItems.service']);

        return response()->json([
            'status' => 'success',
            'data' => $order,
        ]);
    }

    /**
     * Update order status (admin only).
     *
     * @param UpdateOrderStatusRequest $request
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateOrderStatus(UpdateOrderStatusRequest $request, Order $order)
    {
        try {
            $validated = $request->validated();
            $order = $this->orderService->updateOrderStatus($order, $validated['status']);

            return response()->json([
                'status' => 'success',
                'message' => 'تم تحديث حالة الطلب بنجاح',
                'data' => $order->load(['user', 'orderItems']),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء تحديث حالة الطلب: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get dashboard statistics (admin only).
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dashboard(Request $request)
    {
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $processingOrders = Order::where('status', 'processing')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();
        $totalRevenue = Order::where('status', 'delivered')->sum('total_amount');

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_orders' => $totalOrders,
                'pending_orders' => $pendingOrders,
                'processing_orders' => $processingOrders,
                'completed_orders' => $completedOrders,
                'delivered_orders' => $deliveredOrders,
                'total_revenue' => $totalRevenue,
            ],
        ]);
    }
}
