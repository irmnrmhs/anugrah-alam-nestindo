<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'products_id',
        'kode',
        'item',
        'spesification',
        'price',
        'ket',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'products_id');
    }
}
