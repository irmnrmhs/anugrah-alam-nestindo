<?php

namespace App\Models;

use App\Http\Controllers\RawMaterialController;
use Illuminate\Database\Eloquent\Model;

class Arrival extends Model
{
    protected $fillable = [
        'kode',
        'dcertificates_id',
        'cars_id',
        'employees_id', 
        // 'receivers_id', 
        'tgl_kedatangan',
        'kondisi',
        'keterangan',
    ];

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

    public function containers()
    {
        return $this->hasMany(Container::class, 'arrivals_id');
    }

    // public function rawMaterial()
    // {
    //     return $this->belongsTo(RawMaterial::class, 'arrivals_id');
    // }
}
