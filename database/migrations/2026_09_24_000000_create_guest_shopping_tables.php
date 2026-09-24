<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guest_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();
        });

        foreach (['cart_items', 'wishlist_items'] as $tableName) {
            Schema::create($tableName, function (Blueprint $table) use ($tableName) {
                $table->id();
                $table->foreignUuid('guest_session_id')->nullable()->constrained('guest_sessions')->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
                if ($tableName === 'cart_items') {
                    $table->unsignedInteger('quantity')->default(1);
                }
                $table->timestamps();
                $table->index(['user_id', 'product_id']);
                $table->index(['guest_session_id', 'product_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('wishlist_items');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('guest_sessions');
    }
};
