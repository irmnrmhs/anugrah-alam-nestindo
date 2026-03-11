<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SteamUpload extends Model
{
    protected $fillable = [
        'fproducts_id',
        'file',
    ];

    public function fproduct()
    {
        return $this->belongsTo(FinishedProduct::class, 'fproducts_id');
    }
}
