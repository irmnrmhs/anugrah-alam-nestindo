<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WBHouse extends Model
{
    protected $fillable = [
        'kode',
        'nama',
        'alamat',
        'area',
        'kapasitas',
    ];

    // public function dcertificates()
    // {
    //     return $this->hasMany(Dcertificate::class, 'wbhouses_id');
    // }
}
