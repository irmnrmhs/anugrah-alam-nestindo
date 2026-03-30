<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FpAlum extends Model
{
    protected $fillable = [
        'products_id',
        'tgl',
        'kadar_aluminium',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'products_id');
    }
}
