<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'depts_id',
        'no',
        'name',
        'rev'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'depts_id');
    }
}
