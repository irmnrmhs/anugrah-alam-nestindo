<?php

namespace App\Models;

use Dom\Document;
use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    protected $fillable = [
        'depts_id',
        'kode',
        'proses',
        'ket',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'depts_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'steps_id');
    }
}
