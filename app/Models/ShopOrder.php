<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopOrder extends Model
{
    protected $fillable = [
        'order_number', 'user_id', 'customer_address_id', 'items', 'subtotal', 'delivery_address',
        'latitude', 'longitude', 'payment_method', 'payment_status', 'status',
        'cashfree_order_id', 'cashfree_payment_session_id',
    ];

    protected function casts(): array
    {
        return ['items' => 'array', 'subtotal' => 'decimal:2', 'latitude' => 'float', 'longitude' => 'float'];
    }
}
