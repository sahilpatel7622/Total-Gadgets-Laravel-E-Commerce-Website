<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{public function up(): void
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn('address');
    });

    Schema::create('order_details', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('order_id');
        $table->string('name');
        $table->string('number');
        $table->string('email');
        $table->text('address');
        $table->timestamps();

        $table->foreign('order_id')
            ->references('id')
            ->on('orders')
            ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_details');
        Schema::table('orders', function (Blueprint $table) {
            $table->text('address')->nullable();
        });
    }

};
