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
        Schema::create('specifications', function (Blueprint $table) {
            $table->id();
            // $table->string('os');
            // $table->string('cpu');
            // $table->string('speed_cpu');
            // $table->string('gpu');
            // $table->string('ram');
            // $table->string('rom');
            // $table->string('screen');
            // $table->string('camera');
            // $table->string('screen_size');
            // $table->string('battery');
            // $table->unsignedBigInteger('detail_product_id')->nullable();
            // $table->date('release_date');
            // $table->timestamps();
            // $table->foreign('detail_product_id')->references('id')->on('detail_products')->onDelete('cascade');

            // $table->index('detail_product_id');
            $table->string('spec_name');
            $table->string('spec_value');
            $table->unsignedBigInteger('detail_product_id')->nullable();
            $table->timestamps();

            $table->foreign('detail_product_id')->references('id')->on('detail_products')->onDelete('cascade');

            $table->index('detail_product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specifications');
    }
};
