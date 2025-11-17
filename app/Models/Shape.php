<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shape extends Model
{
    protected $fillable = [
        'kode', 'jenis_bentuk',
    ];

    public function Grades()
    {
        return $this->hasMany(Grade::class, 'shapes_id');
    }
}
