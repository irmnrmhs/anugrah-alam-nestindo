<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = 
    [
        'kode', 
        'nama',
        'alamat',
        'no_telp',
        'fax',
        'negara',
    ];
}
