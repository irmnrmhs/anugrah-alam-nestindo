<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'grades_id',
        'item',
        'item_cn',
        'specification',
        'price',
        'ket',
    ];

    public function grade()
    {
        return $this->belongsTo(FpGrade::class, 'grades_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'orders_id');
    }
}
