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
        Schema::create('detail_products', function (Blueprint $table) {
            $table->id();
            $table->string('color')->nullable();
            // $table->integer('stock')->default(0);
            $table->integer('price')->default(0);
            $table->integer('sale_price')->default(0);
            $table->string('version')->nullable();
            // $table->boolean('status')->default(true)->comment('true: con hang false: het hang');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->timestamps();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            $table->index('product_id');
            $table->index('color');
            $table->index('version');
            // $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_products');
    }
};
