<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();

            $table->string('name', 30);

            $table->unsignedBigInteger('parent_id')->default(0);

            $table->string('status', 30);

            // Optional timestamps
            // $table->timestamps();

            // Optional self relation
            // $table->foreign('parent_id')
            //     ->references('id')
            //     ->on('locations')
            //     ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};