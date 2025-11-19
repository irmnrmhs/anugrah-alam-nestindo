<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    protected $fillable = [
        // 'kode',
        // 'arrivals_id',
        'biji',
        'berat',
        // 'kadar_air'
    ];

    // public function containers()
    // {
    //     return $this->hasMany(Container::class, 'raw_materials_id');
    // }

    // public function arrival()
    // {
    //     return $this->belongsTo(Arrival::class, 'arrivals_id');
    // }
}
