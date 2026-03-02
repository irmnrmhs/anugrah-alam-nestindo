<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Officer extends Model
{
    protected $fillable = [
        'nama'
    ];

    public function schedules()
    {
        return $this->hasMany('officers_id');
    }
}
