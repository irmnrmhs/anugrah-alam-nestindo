<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemDetail extends Model
{
    protected $fillable = [
        'grades_id',
        'items_id',
        'specification',
        'price',
        'ket',
    ];

    public function grade()
    {
        return $this->belongsTo(FpGrade::class, 'grades_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'items_id');
    }
    
    public function orders()
    {
        return $this->hasMany(Order::class, 'items_id');
    }
}
