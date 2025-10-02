<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InStock extends Model
{
    //
    protected $fillable = [
        'detail_product_id',
        'store_location_id',
        'stock',
    ];

    function detailProduct()
    {
        return $this->belongsTo(DetailProduct::class, 'detail_product_id');
    }

    function storeLocation()
    {
        return $this->belongsTo(StoreLocation::class, 'store_location_id');
    }
}
