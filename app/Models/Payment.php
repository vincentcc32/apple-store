<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //
    protected $fillable = [
        'order_id',
        'payment_gateway',
        'bank_code',
        'response_code',
        'transaction_id',
        'transaction_status',
        'pay_date',

    ];

    function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
