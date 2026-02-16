<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pull extends Model
{
    protected $fillable = [
        'histories_id',
        'employees_id',
        'tanggal',
        'biji',
        'keterangan',
        'shift',
        'status'
    ];

    public function history()
    {
        return $this->belongsTo(History::class, 'histories_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employees_id');
    }
}
