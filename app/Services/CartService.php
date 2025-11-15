<?php

namespace App\Services;

use App\Models\Cart;

class CartService
{
    /**
     * جلب كل الكارت الخاصة باليوزر
     */
    public function getUserCart($userId)
    {
        return Cart::where('user_id', $userId)
                   ->with('order') // لو عايز تجيب الأوردر المرتبط
                   ->get();
    }

    /**
 * حذف عنصر من الكارت
 */
public function removeFromCart(Cart $cart)
{
    $cart->delete();
}

/**
 * تفريغ كارت المستخدم
 */
public function clearCart($user)
{
    Cart::where('user_id', $user->id)->delete();
}


    

}
