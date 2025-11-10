<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'ikh',
        'nama',
        'alamat',
        'telp',
        'fax',
        'negara',
    ];
}
