<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RmResult extends Model
{
    protected $fillable = [
        // 'types_id',
        'rms_id',
        'kadar_air',
        'kadar_nitrit',
        'kadar_aluminium',
        'ccp1'
    ];

    // public function type()
    // {
    //     return $this->belongsTo(TestType::class, 'types_id');
    // }

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class, 'rms_id');
    }
}
