<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specification extends Model
{
    //
    protected $fillable = [
        'spec_name',
        'spec_value',
        'detail_product_id',
    ];

    function detailProduct()
    {
        return $this->belongsTo(DetailProduct::class, 'detail_product_id');
    }
}
