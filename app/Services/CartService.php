<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;

class CartService
{
    /**
     * Get user's cart items with order relationship.
     *
     * @param \App\Models\User|int $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserCart($user)
    {
        $userId = is_object($user) ? $user->id : $user;
        
        return Cart::where('user_id', $userId)
            ->with(['order.services', 'order.location'])
            ->latest()
            ->get();
    }

    /**
     * Get cart total price.
     *
     * @param \App\Models\User|int $user
     * @return float
     */
    public function getCartTotal($user): float
    {
        $userId = is_object($user) ? $user->id : $user;
        
        return Cart::where('user_id', $userId)
            ->whereNotNull('price')
            ->sum('price');
    }

    /**
     * Add item to cart (creates cart from order).
     * Note: This method creates a cart entry when an order is accepted.
     *
     * @param \App\Models\User $user
     * @param int $orderId
     * @return Cart
     */
    public function addToCart($user, int $orderId): Cart
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $cart = Cart::updateOrCreate(
            ['order_id' => $order->id],
            [
                'user_id' => $user->id,
                'price' => $order->final_price,
                'admin_status' => $order->admin_status,
                'order_status' => $order->order_status,
                'rejection_reason' => $order->rejection_reason,
            ]
        );

        return $cart->load(['order.services', 'order.location']);
    }

    /**
     * Update cart item.
     *
     * @param Cart $cart
     * @param array $data
     * @return Cart
     */
    public function updateCartItem(Cart $cart, array $data): Cart
    {
        $cart->update($data);
        return $cart->fresh()->load(['order.services', 'order.location']);
    }

    /**
     * Remove item from cart.
     *
     * @param Cart $cart
     * @return bool
     */
    public function removeFromCart(Cart $cart): bool
    {
        return $cart->delete();
    }

    /**
     * Clear user's cart.
     *
     * @param \App\Models\User $user
     * @return int Number of deleted items
     */
    public function clearCart($user): int
    {
        $userId = is_object($user) ? $user->id : $user;
        return Cart::where('user_id', $userId)->delete();
    }

    /**
     * Get cart details with relationships.
     *
     * @param Cart $cart
     * @return Cart
     */
    public function getCartDetails(Cart $cart): Cart
    {
        return $cart->load(['order.services', 'order.location', 'user']);
    }

    /**
     * Authorize access to cart.
     *
     * @param Cart $cart
     * @return void
     */
    public function authorize(Cart $cart): void
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بالوصول إلى هذا العنصر');
        }
    }
}
