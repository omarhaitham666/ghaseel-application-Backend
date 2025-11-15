<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminAcceptOrderRequest;
use App\Http\Requests\AdminRejectOrderRequest;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Http\Resources\DashboardStatisticsResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderResourceCollection;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
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
     * @return JsonResponse
     */
    public function getAllOrders(Request $request): JsonResponse
    {
        try {
            $orders = $this->orderService->getAllOrders();

            return (new OrderResourceCollection($orders))->additional([
                'status' => 'success',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء جلب الطلبات: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get order details (admin only).
     *
     * @param Request $request
     * @param Order $order
     * @return JsonResponse
     */
    public function getOrder(Request $request, Order $order): JsonResponse
    {
        try {
            $order = $this->orderService->getOrderDetails($order);

            return response()->json([
                'status' => 'success',
                'data' => new OrderResource($order),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء جلب تفاصيل الطلب: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update order status (admin only).
     *
     * @param UpdateOrderStatusRequest $request
     * @param Order $order
     * @return JsonResponse
     */
    public function updateOrderStatus(UpdateOrderStatusRequest $request, Order $order): JsonResponse
    {
        try {
            $validated = $request->validated();
            $order = $this->orderService->updateOrderStatus($order, $validated['status']);

            return response()->json([
                'status' => 'success',
                'message' => 'تم تحديث حالة الطلب بنجاح',
                'data' => new OrderResource($order),
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
     * @return JsonResponse
     */
    public function dashboard(Request $request): JsonResponse
    {
        try {
            $statistics = $this->orderService->getDashboardStatistics();

            return response()->json([
                'status' => 'success',
                'data' => new DashboardStatisticsResource($statistics),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء جلب إحصائيات لوحة التحكم: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Accept order (admin only).
     *
     * @param AdminAcceptOrderRequest $request
     * @param Order $order
     * @return JsonResponse
     */
    public function acceptOrder(AdminAcceptOrderRequest $request, Order $order): JsonResponse
    {
        try {
            $validated = $request->validated();
            $order = $this->orderService->adminAccept($order, $validated['final_price']);

            return response()->json([
                'status' => 'success',
                'message' => 'تم قبول الطلب بنجاح',
                'data' => new OrderResource($order),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء قبول الطلب: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Reject order (admin only).
     *
     * @param AdminRejectOrderRequest $request
     * @param Order $order
     * @return JsonResponse
     */
    public function rejectOrder(AdminRejectOrderRequest $request, Order $order): JsonResponse
    {
        try {
            $validated = $request->validated();
            $order = $this->orderService->adminReject($order, $validated['rejection_reason']);

            return response()->json([
                'status' => 'success',
                'message' => 'تم رفض الطلب بنجاح',
                'data' => new OrderResource($order),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء رفض الطلب: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete order (admin only).
     *
     * @param int $orderId
     * @return JsonResponse
     */
    public function adminDeleteOrder(int $orderId): JsonResponse
    {
        try {
            $this->orderService->deleteOrder($orderId);

            return response()->json([
                'status' => 'success',
                'message' => 'تم حذف الطلب بنجاح',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'الطلب غير موجود',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء حذف الطلب: ' . $e->getMessage(),
            ], 500);
        }
    }
}
