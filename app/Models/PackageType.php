<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackageType extends Model
{
    protected $fillable = [ 'type' ];

    public function packages()
    {
        return $this->hasMany(PackageType::class, 'types_id');
    }
}
