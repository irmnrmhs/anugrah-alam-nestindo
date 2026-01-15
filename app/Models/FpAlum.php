<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FpAlum extends Model
{
    protected $fillable = [
        'products_id',
        'kadar_aluminium',
    ];

    public function product()
    {
        return $this->belongsTo(FinishedProduct::class, 'products_id');
    }
}
