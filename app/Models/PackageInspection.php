<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageInspection extends Model
{
    protected $fillable = [
        'packages_id',
        'tanggal',
        'hasil',
    ];

    public function package()
    {
        return $this->belongsTo('packages_id');
    }
}
