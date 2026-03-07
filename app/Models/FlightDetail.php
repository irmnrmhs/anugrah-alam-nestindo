<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlightDetail extends Model
{
    protected $fillable = [
        'exports_id',
        'flights_id',
    ];

    public function export()
    {
        return $this->belongsTo(Export::class, 'exports_id');
    }

    public function flight()
    {
        return $this->belongsTo(Flight::class, 'flights_id');
    }
}
