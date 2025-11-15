<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminAcceptOrderRequest;
use App\Http\Requests\AdminRejectOrderRequest;
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




  //  public function acceptOrder(AdminAcceptOrderRequest $request, Order $order)
//{
  //  try {
    //    $validated = $request->validated();

      //  $order = $this->orderService->acceptOrder(
        //    $order,
          //  $validated['final_price']
        //);

        //return response()->json([
          //  'status' => 'success',
            //'message' => 'تم قبول الطلب بنجاح',
  //          'd//ata' => $order->load(['user', 'orderItems']),
        //]);
    //} catch (\Exception $e) {
      //  return response()->json([
        //    'status' => 'error',
          //  'message' => 'حدث خطأ أثناء قبول الطلب: ' . $e->getMessage(),
        //], 500);
    //}
//}


//public function rejectOrder(AdminRejectOrderRequest  $request, Order $order)
//{
    //try {
    //    $validated = $request->validated();

      //  $order = $this->orderService->rejectOrder(
           // $order,
          //  $validated['reason']
       // );

      //  return response()->json([
            //'status' => 'success',
           // 'message' => 'تم رفض الطلب بنجاح',
            //'data' => $order->load(['user', 'orderItems']),
       // ]);
   // } catch (\Exception $e) {
        //return response()->json([
          //  'status' => 'error',
    //        'message' => 'حدث خطأ أثناء رفض الطلب: ' . $e->getMessage(),
      //  ], 500);
   // }
//}


    /**
     * Get dashboard statistics (admin only).
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dashboard()
{
    return response()->json([
        'status' => 'success',
        'data' => [
            'total_orders' => Order::count(),

            'admin_pending' => Order::where('admin_status', 'pending')->count(),
            'admin_accepted' => Order::where('admin_status', 'accepted')->count(),
            'admin_rejected' => Order::where('admin_status', 'rejected')->count(),

            'processing_orders' => Order::where('order_status', 'processing')->count(),
            'completed_orders' => Order::where('order_status', 'completed')->count(),
            'delivered_orders' => Order::where('order_status', 'delivered')->count(),

            'total_revenue' => Order::where('order_status', 'delivered')->sum('final_price'),
        ]
    ]);
}



   public function acceptOrder(AdminAcceptOrderRequest $request, Order $order)
{
    $validated = $request->validated();

    $order = $this->orderService->adminAccept($order, $validated['final_price']);

    return response()->json([
        'status' => 'success',
        'message' => 'تم قبول الطلب بنجاح',
        'data' => $order,
    ]);
}


public function rejectOrder(AdminRejectOrderRequest $request, Order $order)
{
    $validated = $request->validated();

    $order = $this->orderService->adminReject($order, $validated['rejection_reason']);

    return response()->json([
        'status' => 'success',
        'message' => 'تم رفض الطلب بنجاح',
        'data' => $order,
    ]);
}



public function adminDeleteOrder($orderId)
{
    $user = auth()->user();

    // التأكد أن المستخدم مسجل دخول وأنه أدمن
    if (!$user || $user->role !== 'admin') {
        return response()->json([
            'status' => 'error',
            'message' => 'غير مصرح لك'
        ], 403);
    }

    // البحث عن الأوردر
    $order = \App\Models\Order::find($orderId);

    if (!$order) {
        return response()->json([
            'status' => 'error',
            'message' => 'الأوردر غير موجود'
        ], 404);
    }

    // حذف الأوردر فقط، بدون أي علاقة بالكارت
    $order->delete();

    return response()->json([
        'status' => 'success',
        'message' => 'تم حذف الأوردر بنجاح'
    ]);
}



}
