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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->string('payment_gateway')->comment('cổng thanh toán vnpay momo,...');
            $table->string('bank_code')->comment('mã ngân hàng');
            $table->string('response_code')->comment('hành vi lỗi do người dùng: 00 thành công, ... lên doc mà đọc ');
            $table->string('transaction_id')->comment('mã giao dịch trên hệ thống');
            $table->string('transaction_status')->comment('hành vi lỗi do hệ thống: 00 thành công, ... lên doc mà đọc');
            $table->dateTime('pay_date');
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

            $table->index('order_id');
            $table->index('payment_gateway');
            $table->index('bank_code');
            $table->index('response_code');
            $table->index('transaction_id');
            $table->index('transaction_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
