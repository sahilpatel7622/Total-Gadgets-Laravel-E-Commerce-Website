<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wishlists', function ($table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('user')
                ->cascadeOnDelete();
            $table->foreignId('product_id')
                ->constrained('product')
                ->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};
