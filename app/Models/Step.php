<?php

namespace App\Models;

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
}
