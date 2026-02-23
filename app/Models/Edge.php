<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Edge extends Model
{
    protected $fillable = [
        'histories_id',
        'employees_id',
        'tanggal',
        'biji',
        'hancuran',
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
