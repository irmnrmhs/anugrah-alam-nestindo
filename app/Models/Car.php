<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'plat',
        'merk',
    ];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'cars_id');
    }
}
