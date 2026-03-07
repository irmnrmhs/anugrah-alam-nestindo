<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'types_id',
        'bahan',
        'satuan',
        'min',
        'max',
        'toleransi',
    ];

    public function type()
    {
        return $this->belongsTo(Package::class, 'types_id');
    }

    public function inspections()
    {
        return $this->hasMany('packages_id');
    }
}
