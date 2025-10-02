<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    //
    protected $fillable = [
        'detail_order_id',
        'detail_product_id',
        'rating',
        'content',
    ];

    function detailOrder()
    {
        return $this->belongsTo(DetailOrder::class, 'detail_order_id');
    }

    function detailProduct()
    {
        return $this->belongsTo(DetailProduct::class, 'detail_product_id');
    }
}
