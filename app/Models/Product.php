<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'histories_id',
        'employees_id',
        'grades_id',
        'tanggal',
        'kode',
        'biji',
        'berat',
        'ket',
    ];

    public function history()
    {
        return $this->belongsTo(History::class, 'histories_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employees_id');
    }

    public function grade()
    {
        return $this->belongsTo(FpGrade::class, 'grades_id');
    }

    public function fresults()
    {
        return $this->hasMany(FpResult::class, 'products_id');
    }

    public function alResults()
    {
        return $this->hasMany(FpAlum::class, 'products_id');
    }

    public function steams()
    {
        return $this->hasMany(Steam::class, 'products_id');
    }

    // public function getBeratAttribute()
    // {
    //     return $this->history()->gcolor()->sum('berat');
    // }

    public static function generateCode($grade, $pi)
    {
        return $grade
            . '-' .
            preg_replace('/[^A-Za-z0-9]/', '', $pi);
    }
}
