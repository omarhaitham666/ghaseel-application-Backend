<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Service;
use App\Models\User;

class CartService
{
    /**
     * Add item to cart.
     *
     * @param User $user
     * @param int $serviceId
     * @param int $quantity
     * @return Cart
     */
    public function addToCart(User $user, int $serviceId, int $quantity): Cart
    {
        $service = Service::findOrFail($serviceId);

        // Check if service is active
        if (!$service->is_active) {
            throw new \Exception('الخدمة غير متاحة حالياً');
        }

        // Check if item already exists in cart
        $cartItem = Cart::where('user_id', $user->id)
            ->where('service_id', $serviceId)
            ->first();

        if ($cartItem) {
            // Update quantity
            $cartItem->update(['quantity' => $cartItem->quantity + $quantity]);
            return $cartItem->fresh();
        }

        // Create new cart item
        return Cart::create([
            'user_id' => $user->id,
            'service_id' => $serviceId,
            'quantity' => $quantity,
        ]);
    }

    /**
     * Update cart item quantity.
     *
     * @param Cart $cart
     * @param int $quantity
     * @return Cart
     */
    public function updateCartItem(Cart $cart, int $quantity): Cart
    {
        $cart->update(['quantity' => $quantity]);
        return $cart->fresh();
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
     * Get user's cart items.
     *
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserCart(User $user)
    {
        return Cart::where('user_id', $user->id)
            ->with('service')
            ->get();
    }

    /**
     * Clear user's cart.
     *
     * @param User $user
     * @return int
     */
    public function clearCart(User $user): int
    {
        return Cart::where('user_id', $user->id)->delete();
    }

    /**
     * Calculate cart total.
     *
     * @param User $user
     * @return float
     */
    public function getCartTotal(User $user): float
    {
        $cartItems = $this->getUserCart($user);
        $total = 0;

        foreach ($cartItems as $item) {
            $total += $item->service->price * $item->quantity;
        }

        return $total;
    }
}

