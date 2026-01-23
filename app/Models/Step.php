<?php

namespace App\Models;

use Dom\Document;
use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    protected $fillable = [
        'employees_id',
        'kode',
        'proses',
        'ket',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employees_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'steps_id');
    }
}
