<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WBHouse extends Model
{
    protected $fillable = [
        'kode',
        'nama',
        'alamat',
        'areas_id',
        'kapasitas',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class, 'areas_id');
    }

    // public function dcertificates()
    // {
    //     return $this->hasMany(Dcertificate::class, 'wbhouses_id');
    // }
}
