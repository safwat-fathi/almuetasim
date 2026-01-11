<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'guest_info',
        'shipping_address',
        'total_amount',
        'payment_method',
        'payment_status',
        'status',
    ];

    protected $casts = [
        'guest_info' => 'array',
        'total_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
