<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RmStock extends Model
{
    protected $fillable = [
        'rms_id',
        'employees_id',
        'tgl_keluar',
        'biji_keluar',
        'berat_keluar',
        'keterangan'
    ];

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class, 'rms_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employees_id');
    }
}