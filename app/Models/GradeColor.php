<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeColor extends Model
{
    protected $fillable = [
        'rms_id',
        'employees_id',
        'feathers_id',
        'colors_id',
        // 'kode',
        'biji',
        'berat',
        'other',
        'tanggal',
    ];

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class, 'rms_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employees_id');
    }

    public function feather()
    {
        return $this->belongsTo(Feather::class, 'feathers_id');
    }

    public function color()
    {
        return $this->belongsTo(Color::class, 'colors_id');
    }
}
