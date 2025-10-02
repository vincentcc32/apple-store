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
        Schema::create('in_stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('detail_product_id')->nullable();
            $table->unsignedBigInteger('store_location_id')->nullable();
            $table->integer('stock')->default(0);
            $table->timestamps();

            $table->foreign('detail_product_id')->references('id')->on('detail_products')->onDelete('cascade');
            $table->foreign('store_location_id')->references('id')->on('store_locations')->onDelete('cascade');

            $table->index('detail_product_id');
            $table->index('store_location_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('in_stores');
    }
};
