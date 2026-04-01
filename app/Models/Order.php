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
        'cartons',
        'amount_fob',
        'amount_cif',
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
        return $this->belongsTo(ItemDetail::class, 'items_id');
    }

    public function pack()
    {
        return $this->hasOne(Packing::class, 'orders_id');
    }

    public function steams()
    {
        return $this->hasMany(Steam::class, 'orders_id');
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
