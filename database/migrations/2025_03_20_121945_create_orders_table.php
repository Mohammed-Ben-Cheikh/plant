<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice');
            $table->string('slug')->unique();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('plant_id')->constrained('plants');
            $table->integer('quantity');
            $table->decimal('total', 10, 2);
            $table->enum(
                'status',
                ['pending', 'success', 'annulled']
            )->default('pending');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
