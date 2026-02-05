<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ccp1 extends Model
{
    protected $fillable = [
        'rms_id',
        'tgl',
        'ccp1'
    ];

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class, 'rms_id');
    }
}
