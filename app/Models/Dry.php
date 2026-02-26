<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Dry extends Model
{
    protected $fillable = [
        'histories_id',
        'employees_id',
        'tanggal',
        'biji',
        'waktu_in',
        'waktu_out',
        'shift',
    ];

    protected $casts = [
        'waktu_in'  => 'datetime:H:i',
        'waktu_out' => 'datetime:H:i',
        'tanggal'    => 'date:Y-m-d',
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
