<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'kode',
        'nama',
        'alamat',
        'no_telp',
        'categories_id', 
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'categories_id');
    }

    // public function dcertificates()
    // {
    //     return $this->hasMany(Dcertificate::class, 'suppliers_id');
    // }
}
