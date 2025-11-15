<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

      protected $fillable = [
        'user_id',
        'location_id',
        'delivery_type',
        'pickup_date',
        'pickup_time',
        'delivery_date',
        'delivery_time',
        'notes',
        'admin_status',
        'order_status',
        'final_price',
        'rejection_reason',
    ];

    public function services()
    {
        return $this->belongsToMany(Service::class, 'order_services');
    }

    public function location()
    {
        return $this->belongsTo(UserLocation::class, 'location_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function cart()
{
    return $this->hasOne(Cart::class);
}

}
