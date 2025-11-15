<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'price',          // السعر النهائي للخدمة
        'admin_status',   // pending, accepted, rejected
        'order_status',
        'order_id',
        'rejection_reason',
    ];

    /**
     * Get the user that owns the cart.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the service that belongs to the cart.
     */
   

    public function order()
{
    return $this->belongsTo(Order::class);
}

}
