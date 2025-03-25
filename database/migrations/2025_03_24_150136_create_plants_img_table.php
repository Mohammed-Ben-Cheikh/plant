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
        Schema::create('plants_img', function (Blueprint $table) {
            $table->id();
            $table->string('image_url');
            $table->boolean('is_primary')->default(false);
            $table->foreignId('plant_id')->constrained('plants');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plants_img');
    }
};
