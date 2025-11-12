<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Support\Str;

class OrderService
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Create order from cart.
     *
     * @param User $user
     * @param array $data
     * @return Order
     */
    public function createOrder(User $user, array $data): Order
    {
        $cartItems = $this->cartService->getUserCart($user);

        if ($cartItems->isEmpty()) {
            throw new \Exception('السلة فارغة');
        }

        $total = $this->cartService->getCartTotal($user);

        // Generate unique order number
        $orderNumber = 'ORD-' . strtoupper(Str::random(8)) . '-' . time();

        // Create order
        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => $orderNumber,
            'total_amount' => $total,
            'status' => 'pending',
            'address' => $data['address'],
            'phone' => $data['phone'],
            'notes' => $data['notes'] ?? null,
        ]);

        // Create order items
        foreach ($cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'service_id' => $cartItem->service_id,
                'service_name' => $cartItem->service->name,
                'service_price' => $cartItem->service->price,
                'quantity' => $cartItem->quantity,
                'subtotal' => $cartItem->service->price * $cartItem->quantity,
            ]);
        }

        // Clear cart
        $this->cartService->clearCart($user);

        return $order->load('orderItems');
    }

    /**
     * Update order status.
     *
     * @param Order $order
     * @param string $status
     * @return Order
     */
    public function updateOrderStatus(Order $order, string $status): Order
    {
        $order->update(['status' => $status]);
        return $order->fresh();
    }

    /**
     * Get user's orders.
     *
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserOrders(User $user)
    {
        return Order::where('user_id', $user->id)
            ->with('orderItems')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get all orders (for admin).
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllOrders()
    {
        return Order::with(['user', 'orderItems'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get order by ID.
     *
     * @param int $id
     * @return Order
     */
    public function getOrderById(int $id): Order
    {
        return Order::with(['user', 'orderItems'])->findOrFail($id);
    }

    /**
     * Get orders by status.
     *
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getOrdersByStatus(string $status)
    {
        return Order::where('status', $status)
            ->with(['user', 'orderItems'])
            ->orderBy('created_at', 'desc')
            ->get();
    }
}

