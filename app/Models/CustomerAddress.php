<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    protected $fillable = [
        'user_id', 'label', 'recipient_name', 'mobile', 'address_line',
        'pincode', 'latitude', 'longitude', 'is_default',
    ];

    protected function casts(): array
    {
        return ['latitude' => 'float', 'longitude' => 'float', 'is_default' => 'boolean'];
    }
}
