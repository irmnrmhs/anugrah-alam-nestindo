<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FpResult extends Model
{
    protected $fillable = [
        'products_id',
        'tgl',
        'kadar_air',
        'kadar_nitrit',
        'hasil',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'products_id');
    }
}