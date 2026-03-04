<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Packing extends Model
{
    protected $fillable = [
        'orders_id',
        'tanggal',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'orders_id');
    }
}
