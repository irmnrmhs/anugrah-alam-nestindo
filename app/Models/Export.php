<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Export extends Model
{
    protected $fillable = [
        'inv',
        'customers_id',
        'contract_no',
        'date',
        'by',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customers_id');
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'exports_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'exports_id');
    }

    public function dflights()
    {
        return $this->hasMany(FlightDetail::class, 'flights_id');
    }

    public function certificates()
    {
        return $this->hasMany(AnalysisCertficate::class, 'exports_id');
    }
}
