<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $fillable = [
        'identifiers_id',
        'asal',
        'tujuan',
        'biji',
        'berat'
    ];

    public function identifier()
    {
        return $this->belongsTo(ProductIdentifier::class, 'identifiers_id');
    }

    public function edges()
    {
        return $this->hasMany(Edge::class, 'histories_id');
    }
}
