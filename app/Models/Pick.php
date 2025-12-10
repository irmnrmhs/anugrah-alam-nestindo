<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pick extends Model
{
    protected $fillable = [
        'histories_id',
        'employees_id',
        'tgl_mulai',
        'biji_masuk',
        'berat_masuk',
        'tgl_selesai',
        'biji_keluar',
        'berat_keluar',
        'keterangan',
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
