<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailOrder extends Model
{
    //
    protected $fillable = [
        'order_id',
        'detail_product_id',
        'quantity',
        'sum_price',
    ];

    function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    function detailProduct()
    {
        return $this->belongsTo(DetailProduct::class, 'detail_product_id');
    }
}
