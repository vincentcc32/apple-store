<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    //
    protected $fillable = [
        'detail_product_id',
        'path',
    ];

    function detailProduct()
    {
        return $this->belongsTo(DetailProduct::class, 'detail_product_id');
    }
}
