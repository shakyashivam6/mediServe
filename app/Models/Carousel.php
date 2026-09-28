<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[Fillable(['image', 'title', 'link', 'sort_order', 'is_active'])]
class Carousel extends Model
{
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Publicly-servable URL for the uploaded slide image — stored on the
     * `public` disk (storage/app/public/carousels, linked to public/storage
     * via `php artisan storage:link`) so the storefront can render it
     * directly.
     */
    public function getImageUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->image);
    }

    /**
     * Slides shown on the storefront, in display order — used to decide
     * whether the homepage carousel renders at all (see StorefrontController
     * and storefront.index).
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('id');
    }
}
