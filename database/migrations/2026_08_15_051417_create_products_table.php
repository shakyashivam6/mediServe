<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Shape follows the bulk-import spreadsheet columns (item_id, drug name,
     * composition, manufacturer, mrp, price, packaging, use of medicine,
     * image0..image9) — see App\Imports\MedicineImport. Not per-Store stock;
     * this is the shared master catalog (roadmap Phase 2). Per-Store stock/
     * batch/expiry is a separate future table that will reference this one.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // The spreadsheet's own slug-style id (e.g.
            // "jr-cold-oral-suspension-1084098") — unique, and what a
            // re-upload of the same/updated sheet upserts against.
            $table->string('item_id')->unique();

            $table->string('name');
            $table->text('composition')->nullable();
            $table->string('manufacturer')->nullable();
            $table->decimal('mrp', 10, 2)->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->string('packaging')->nullable();
            $table->text('uses')->nullable();

            // Up to 10 image URLs from the sheet (image0..image9), kept as
            // a JSON array rather than 10 columns.
            $table->json('images')->nullable();

            // Not present in the import sheet — set manually per product.
            // Default false rather than true: an unreviewed import
            // shouldn't silently mark real medicines as OTC.
            $table->boolean('requires_prescription')->default(false);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
