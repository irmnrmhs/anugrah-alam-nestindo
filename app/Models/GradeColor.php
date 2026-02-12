<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeColor extends Model
{
    protected $fillable = [
        'rms_id',
        'employees_id',
        'gfeathers_id',
        'kode',
        'bj_p',
        'br_p',
        'bj_pb',
        'br_pb',
        'bj_pg',
        'br_pg',
        'other',
        'tgl',
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
