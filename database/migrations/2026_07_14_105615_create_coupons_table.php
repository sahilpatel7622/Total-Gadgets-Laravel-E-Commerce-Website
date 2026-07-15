<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('type', ['fixed', 'percentage']);
            $table->decimal('discount_value', 10, 2);      
            $table->enum('user_type', ['all', 'selected'])->default('all');
            $table->decimal('minimum_order_amount', 10, 2)->default(0);
            $table->unsignedInteger('usage_limit')->nullable();
            $table->unsignedInteger('per_user_limit')->default(1);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};