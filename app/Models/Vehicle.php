<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'exports_id',
        'cars_id',
        'emp_id',
        'berat',
        'kondisi_box',
        'kemasan',
    ];

    public function export()
    {
        return $this->belongsTo(Export::class, 'exports_id');
    }

    public function car()
    {
        return $this->belongsTo(Car::class, 'cars_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_id');
    }
}
