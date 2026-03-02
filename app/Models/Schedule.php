<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'officers_id',
        'flights_id',
        'preshipment',
        'shipment',
    ];

    public function officer()
    {
        return $this->belongsTo(Officer::class, 'officers_id');
    }
}
