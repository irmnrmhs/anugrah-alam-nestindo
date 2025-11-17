<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feather extends Model
{
    protected $fillable = [
        'kode', 'jenis_bulu'
    ];

    public function Grades()
    {
        return $this->hasMany(Grade::class, 'feathers_id');
    }
}
