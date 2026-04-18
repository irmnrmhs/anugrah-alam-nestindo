<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RmAlum extends Model
{
    protected $fillable = [
        'rms_id',
        'tgl',
        'kadar_aluminium',
        'hasil',
    ];

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class, 'rms_id');
    }
}
