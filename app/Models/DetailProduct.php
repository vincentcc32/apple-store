<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailProduct extends Model
{
    //
    protected $fillable = [
        'product_id',
        'stock',
        'price',
        'sale_price',
        'color',
        'version',
    ];

    function images()
    {
        return $this->hasMany(Image::class);
    }

    function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    function inStocks()
    {
        return $this->hasMany(InStock::class);
    }

    function specifications()
    {
        return $this->hasMany(Specification::class);
    }

    function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
