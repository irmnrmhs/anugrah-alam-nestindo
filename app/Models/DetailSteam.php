<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailSteam extends Model
{
    protected $fillable = [
        'orders_id',
        'suhu_preheating',
        'waktu_preheating',
        'suhu_total',
        'waktu_total',
        'jml_tray',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'orders_id');
    }
}
