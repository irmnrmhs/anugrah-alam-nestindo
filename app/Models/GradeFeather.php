<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeFeather extends Model
{
    protected $fillable = [
        'rms_id',
        'employees_id',
        'kode',
        'tanggal',
        'mk',
        'ovl',
        'sdt',
        'pth',
        'hcr',
        'bj_brp',
        'br_brp',
        'bj_bs',
        'br_bs',
        'bj_bb',
        'br_bb',
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
