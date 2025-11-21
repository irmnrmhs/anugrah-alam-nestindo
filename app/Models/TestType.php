<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestType extends Model
{
    protected $fillable = [
        'categories_id',
        'nama_uji',
        'satuan',
        'standar_minimal',
        'standar_maksimal',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'categories_id');
    }

    // public function rmResults()
    // {
    //     return $this->hasMany(RmResult::class, 'types_id');
    // }
}
