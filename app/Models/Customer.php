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

    public function exports()
    {
        return $this->hasMany(Export::class, 'customers_id');
    }
}
