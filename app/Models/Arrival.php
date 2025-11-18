<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Arrival extends Model
{
    protected $fillable = [
        'dcertificates_id',
        'cars_id',
        'drivers_id', 
        // 'receivers_id', 
        'kondisi',
        'keterangan',
    ];
// no skp, mobil, supir, penerima, kondisi, keterang
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employees_id');
    }

    public function car()
    {
        return $this->belongsTo(Car::class, 'cars_id');
    }

    public function dcertificate()
    {
        return $this->belongsTo(Dcertificate::class, 'dcertificates_id');
    }
}
