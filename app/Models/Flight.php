<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    protected $fillable = [
        'flight_no',
        'destination',
        'shipping_mark',
        'estimated_arrival',
    ];

    public function dflights()
    {
        return $this->hasMany(FlightDetail::class, 'flights_id');
    }

    public function schedule()
    {
        return $this->hasOne(Schedule::class, 'flights_id');
    }
}
