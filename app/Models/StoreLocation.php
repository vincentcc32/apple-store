<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreLocation extends Model
{
    //
    protected $fillable =
    [
        'address',
    ];

    public function inStocks()
    {
        return $this->hasMany(InStock::class);
    }
}
