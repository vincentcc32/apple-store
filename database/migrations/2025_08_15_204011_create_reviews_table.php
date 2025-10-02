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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('detail_order_id')->nullable();
            $table->unsignedBigInteger('detail_product_id')->nullable();
            $table->tinyInteger('rating')->default(0)->comment('1-5');
            $table->string('content')->nullable();
            $table->timestamps();

            $table->foreign('detail_order_id')->references('id')->on('detail_orders')->onDelete('cascade');
            $table->foreign('detail_product_id')->references('id')->on('detail_products')->onDelete('cascade');

            $table->index('detail_order_id');
            $table->index('detail_product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
