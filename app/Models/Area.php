<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = [
        'kode',
        'area',
        'kh',
        'keterangan'
    ];

    public function wbhouses()
    {
        return $this->hasMany(WBHouse::class, 'areas_id');
    }
}
