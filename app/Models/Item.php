<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'item',
        'item_cn',
    ];

    public function ditems()
    {
        return $this->hasMany(ItemDetail::class, 'items_id');
    }
}
