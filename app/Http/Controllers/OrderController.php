<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Get user's orders.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $orders = $this->orderService->getUserOrders($user);

        return response()->json([
            'status' => 'success',
            'data' => $orders,
        ]);
    }

    /**
     * Create new order from cart.
     *
     * @param CreateOrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateOrderRequest $request)
    {
        try {
            $user = $request->user();
            $validated = $request->validated();

            $order = $this->orderService->createOrder($user, $validated);

            return response()->json([
                'status' => 'success',
                'message' => 'تم إنشاء الطلب بنجاح',
                'data' => $order,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get specific order.
     *
     * @param Request $request
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, Order $order)
    {
        // Check if order belongs to authenticated user
        if ($order->user_id !== $request->user()->id && !$request->user()->isAdmin()) {
            return response()->json([
                'status' => 'error',
                'message' => 'غير مصرح لك بالوصول إلى هذا الطلب',
            ], 403);
        }

        $order->load(['user', 'orderItems.service']);

        return response()->json([
            'status' => 'success',
            'data' => $order,
        ]);
    }

    /**
     * Get orders by status.
     *
     * @param Request $request
     * @param string $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByStatus(Request $request, string $status)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'status' => 'error',
                'message' => 'غير مصرح لك بالوصول إلى هذا المورد',
            ], 403);
        }

        $orders = $this->orderService->getOrdersByStatus($status);

        return response()->json([
            'status' => 'success',
            'data' => $orders,
        ]);
    }
}
