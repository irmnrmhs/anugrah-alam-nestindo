<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    protected $fillable = [
        'destination',
        'flight_no',
        'shipping_mark',
        'estimated_arrival',
    ];

    public function exports()
    {
        return $this->hasMany(Export::class, 'flights_id');
    }
}
