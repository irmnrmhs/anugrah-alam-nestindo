<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Dry extends Model
{
    protected $fillable = [
        'histories_id',
        'employees_id',
        'tgl_mulai',
        'biji_masuk',
        'berat_masuk',
        'waktu_masuk',
        'tgl_selesai',
        'biji_keluar',
        'berat_keluar',
        'waktu_keluar',
        'keterangan',
        'shift',
        'status'
    ];

    public function getWaktuMasukAttribute($value)
    {
        return $value
            ? Carbon::createFromFormat('H:i:s', $value)->format('H:i')
            : null;
    }

    public function getWaktuKeluarAttribute($value)
    {
        return $value
            ? Carbon::createFromFormat('H:i:s', $value)->format('H:i')
            : null;
    }

    public function history()
    {
        return $this->belongsTo(History::class, 'histories_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employees_id');
    }
}
