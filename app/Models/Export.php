<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Export extends Model
{
    protected $fillable = [
        'inv',
        'customers_id',
        'flights_id',
        'contract_no',
        'date',
        'by',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customers_id');
    }

    public function flight()
    {
        return $this->belongsTo(Flight::class, 'flights_id');
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'exports_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'exports_id');
    }
}
