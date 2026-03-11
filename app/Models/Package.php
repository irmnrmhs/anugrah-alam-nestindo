<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'types_id',
        'bahan',
        'satuan',
        'panjang',
        'lebar',
        'tinggi',
        'lainnya',
        'toleransi',
    ];

    public function type()
    {
        return $this->belongsTo(PackageType::class, 'types_id');
    }

    public function inspections()
    {
        return $this->hasMany('packages_id');
    }
}
