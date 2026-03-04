<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'exports_id',
        'packaging',
        'label',
        'net',
        'gross',
        'amount_fob',
        'amout_cif',
    ];

    public function getQtyAttribute()
    {
        $qty = $this->net / $this->label;
        return $qty;
    }

    public function getCartonAttribute()
    {
        return "banyak karton";
    }
}
