<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * MVP slice of the Phase 5 Prescription Workflow (roadmap.blade.php) —
     * scoped down from the full spec: no in-app chat, no separate Orders
     * table yet. A Store reviews the upload and contacts the Customer by
     * voice call (outside the software); the outcome is recorded directly
     * on this row rather than a separate order.
     *
     * Store routing is a shared queue: `store_id` starts null and whichever
     * approved Store opens it first claims it, rather than automatic
     * radius/GPS matching — Stores don't reliably have lat/long set yet.
     */
    public function up(): void
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('store_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('captain_id')->nullable()->constrained('users')->nullOnDelete();

            // Uploaded prescription pages — image/PDF paths on the private
            // 'local' disk (storage/app/private/prescriptions/...).
            $table->json('files');
            $table->text('remark')->nullable();
            $table->text('delivery_address');

            // Captured alongside the upload (browser geolocation, optional
            // — a Customer can still submit without sharing it). Lets the
            // claiming Store see distance/ETA and share the pin with a
            // Captain, in-app or not.
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // awaiting_confirmation = Store has sent a priced estimate (the
            // `items`/`total_amount` below) and is waiting on the Customer
            // to accept/reject it — either themselves from their own panel,
            // or verbally on the call, in which case the Store marks
            // confirmed/rejected on the Customer's behalf. dispatched is
            // only reachable from confirmed (see Store\PrescriptionController
            // ::assignCaptain) — a Captain never gets assigned to an order
            // the Customer hasn't agreed to.
            $table->enum('status', ['pending', 'reviewing', 'contacted', 'awaiting_confirmation', 'confirmed', 'dispatched', 'rejected'])
                ->default('pending');

            // Filled in by the Store organizer after examining the upload
            // and calling the Customer — free-form line items rather than a
            // real Orders table (that's a later roadmap phase).
            $table->json('items')->nullable();
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->text('call_notes')->nullable();
            $table->timestamp('called_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();

            // When/why the Customer's own accept/reject decision landed —
            // null if the Store recorded the decision on their behalf
            // instead (see status enum comment above).
            $table->timestamp('customer_decided_at')->nullable();
            $table->text('customer_decision_note')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
