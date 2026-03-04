<?php

namespace App\Models;

use Illuminate\Bus\Batch;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'exports_id',
        'batch_id',
        'items_id',
        'packaging',
        'label',
        'net',
        'gross',
        'amount_fob',
        'amout_cif',
    ];

    public function export()
    {
        return $this->belongsTo(Export::class, 'exports_id');
    }
    
    public function batch()
    {
        return $this->belongsTo(FinishedProduct::class, 'batch_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'items_id');
    }

    public function pack()
    {
        return $this->hasOne(Packing::class, 'orders_id');
    }

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
