<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * One-to-one with `users` (role=store). Store-specific fields live here
     * instead of on `users` so Admin/Captain/Customer rows don't carry
     * a pile of always-null columns.
     */
    public function up(): void
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();

            $table->string('shop_name');
            $table->string('license_no')->nullable();
            $table->string('gst_no')->nullable();

            // Plain numeric inputs for now — swapped for a map picker once
            // a Google Maps API key exists (see project memory).
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // Both optional: a store can register without direct-delivery
            // data and rely entirely on the future delivery-partner
            // integration. "Fast Delivery" is never stored here — it's
            // derived per-order from radius/speed vs an ETA threshold.
            $table->unsignedSmallInteger('delivery_radius_km')->nullable();
            $table->unsignedSmallInteger('delivery_speed_kmph')->nullable();

            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
