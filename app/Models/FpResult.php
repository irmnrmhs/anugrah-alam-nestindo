<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class fpResult extends Model
{
    protected $fillable = [
        'products_id',
        'tgl',
        'kadar_air',
        'kadar_nitrit',
        // 'kadar_aluminium'
    ];

    public function product()
    {
        return $this->belongsTo(FinishedProduct::class, 'products_id');
    }
}