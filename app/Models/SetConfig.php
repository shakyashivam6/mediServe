<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

#[Fillable(['key', 'value'])]
class SetConfig extends Model
{
    protected $table = 'set_config';

    /**
     * Get a setting value by key, falling back to $default when it is
     * not set. Results are cached so this can be called freely (e.g.
     * from a blade layout) without hitting the database each time.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever("set_config.{$key}", function () use ($key, $default) {
            return static::where('key', $key)->value('value') ?? $default;
        });
    }

    /**
     * Create or update a setting value and refresh the cache for it.
     */
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);

        Cache::forever("set_config.{$key}", $value);
    }
}
