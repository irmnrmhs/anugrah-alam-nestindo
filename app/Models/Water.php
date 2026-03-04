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
}
