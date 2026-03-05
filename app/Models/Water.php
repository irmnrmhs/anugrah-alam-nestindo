<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Water extends Model
{
    protected $fillable = [
        'tanggal',
        'nitrit',
        'ph',
        'ozone',
        'organoleptis',
    ];

    public function getResultAttribute()
    {
        if(($this->nitrit < 3 && $this->nitrit >= 0) && ($this->ph < 8.5 && $this->ph >= 6.5) && ($this->ozone < 0.3 && $this->ozone >= 0)){
            return true;
        }
    }
}
