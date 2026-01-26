<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Container extends Model
{
    protected $fillable = [
        'arrivals_id',
        'employees_id',
        'tanggal',
        'biji',
        'berat',
        'keterangan'
    ];

    public function arrival()
    {
        return $this->belongsTo(Arrival::class, 'arrivals_id');
    }
    
    // public function rawMaterial()
    // {
    //     return $this->belongsTo(RawMaterial::class, 'raw_materials_id');
    // }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employees_id');
    }
}
