<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FpGrade extends Model
{
    protected $fillable = [
        'kode',
        'grade',
        'keterangan',
        'status'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'grades_id');
    }
    
    public function items()
    {
        return $this->hasMany(Item::class, 'grades_id');
    }
}
