<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $fillable = [
        'title',
        'description',
        'thumbnail',
        'status',
        'slug',
        'category_id',
        'store_location_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function detailProducts()
    {
        return $this->hasMany(DetailProduct::class);
    }
}
