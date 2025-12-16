<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NestType extends Model
{
    protected $fillable = [
        'type',
        'keterangan'
    ];

    public function steams()
    {
        return $this->hasMany(Steam::class, 'nests_id');
    }
}
