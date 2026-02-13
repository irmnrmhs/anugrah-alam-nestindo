<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $fillable = [
        'kode', 'jenis_warna',
    ];

    public function Grades()
    {
        return $this->hasMany(Grade::class, 'colors_id');
    }

    public function gcolors()
    {
        return $this->hasMany(GradeColor::class, 'colors_id');
    }
}
