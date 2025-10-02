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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('customer_name');
            $table->string('phone', 10);
            $table->string('address');
            $table->string('note')->nullable();
            $table->integer('total_amount');
            $table->integer('shipping_fee');
            $table->boolean('payment_method')->default(true)->comment('true: thanh toan khi nhan hang false: thanh toan online');
            $table->boolean('payment_status')->default(false)->comment('true: thanh toan thanh cong false: thanh toan that bai');
            $table->tinyInteger('status')->default(1)->comment('1: pending 2: shiped 3: completed 4: canceled');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('user_id');
            $table->index('status');
            $table->index('payment_status');
            $table->index('payment_method');
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
