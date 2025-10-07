<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInFo extends Model
{
    //
    protected $fillable = [
        'user_id',
        'PhoneNumber',
        'Address',
        'DistrictCode',
        'WardCode',
    ];

    function user()
    {
        return $this->belongsTo('User');
    }
}
