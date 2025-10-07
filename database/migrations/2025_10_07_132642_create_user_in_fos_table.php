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
        Schema::create('user_in_fos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('PhoneNumber', 10)->nullable();
            $table->string(column: 'Address')->nullable();
            $table->integer('DistrictCode')->nullable()->comment('mã Huyện/thành phố');
            $table->integer('WardCode')->nullable()->comment('mã phường');;

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_in_fos');
    }
};
