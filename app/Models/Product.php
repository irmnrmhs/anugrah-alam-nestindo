<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'histories_id',
        'employees_id',
        'grades_id',
        'kode',
        'tgl_mulai',
        'biji',
        'berat',
        'tgl_selesai'
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
    
    public function fproduct()
    {
        return $this->hasOne(FinishedProduct::class, 'products_id');
    }

    public static function generateCode($grade, $pi)
    {
        return $grade
            . '-' .
            preg_replace('/[^A-Za-z0-9]/', '', $pi);
    }
}
