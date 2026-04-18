<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CcpAlum extends Model
{
    protected $fillable = [
        'rms_id',
        'tgl',
        'ccp_al',
        'hasil',
    ];

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class, 'rms_id');
    }
}
