<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $fillable = [
        'user_id',
        'customer_name',
        'phone',
        'address',
        'note',
        'total_amount',
        'shipping_fee',
        'payment_method',
        'payment_status',
        'status',
    ];

    function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    function detailOrders()
    {
        return $this->hasMany(DetailOrder::class);
    }

    function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
