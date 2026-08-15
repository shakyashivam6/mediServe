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
        Schema::create('set_config', function (Blueprint $table) {
            $table->id();

            // Setting name, e.g. SITELOGO, SITENAME
            $table->string('key', 100)->unique();

            // Setting value (paths, text, numbers, flags... all stored as text)
            $table->text('value')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('set_config');
    }
};
