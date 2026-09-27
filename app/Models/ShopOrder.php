<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopOrder extends Model
{
    protected $fillable = [
        'order_number', 'user_id', 'customer_address_id', 'items', 'subtotal', 'delivery_address',
        'latitude', 'longitude', 'payment_method', 'payment_status', 'status',
        'cashfree_order_id', 'cashfree_payment_session_id', 'fulfillment_status',
        'fulfillment_remark', 'captain_id',
    ];

    protected function casts(): array
    {
        return ['items' => 'array', 'subtotal' => 'decimal:2', 'latitude' => 'float', 'longitude' => 'float'];
    }

    public function getFulfillmentStatusAttribute($value): string
    {
        return filled($value) ? $value : 'pending';
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function captain()
    {
        return $this->belongsTo(User::class, 'captain_id');
    }
}
