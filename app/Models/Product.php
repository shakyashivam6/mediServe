<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'item_id',
    'name',
    'composition',
    'manufacturer',
    'mrp',
    'price',
    'packaging',
    'uses',
    'images',
    'requires_prescription',
    'is_active',
])]
class Product extends Model
{
    protected function casts(): array
    {
        return [
            'mrp' => 'decimal:2',
            'price' => 'decimal:2',
            'images' => 'array',
            'requires_prescription' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * First image from the sheet's image0..image9 columns, for list-view
     * thumbnails. Null if the row had no image URLs.
     */
    public function getThumbnailAttribute(): ?string
    {
        return $this->images[0] ?? null;
    }
}
