<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailDocument extends Model
{
    protected $fillable = [
        'documents_id',
        'employees_id',
        'shift',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class, 'documents_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employees_id');
    }
}
