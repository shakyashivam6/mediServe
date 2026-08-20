<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'user_id',
    'shop_name',
    'license_no',
    'gst_no',
    'order_prefix',
    'latitude',
    'longitude',
    'delivery_radius_km',
    'delivery_speed_kmph',
    'status',
])]
class Store extends Model
{
    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
            'delivery_radius_km' => 'integer',
            'delivery_speed_kmph' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
