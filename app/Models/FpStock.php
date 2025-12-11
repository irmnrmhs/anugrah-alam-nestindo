<?php

namespace App\Models;

use App\Http\Controllers\FinishedController;
use Illuminate\Database\Eloquent\Model;

class FpStock extends Model
{
    protected $fillable = [
        'fproducts_id',
        'employees_id',
        'tgl_keluar',
        'biji_keluar',
        'berat_keluar',
        'keterangan'
    ];

    public function fproducts()
    {
        return $this->hasMany(FinishedProduct::class, 'fproducts_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employees_id');
    }
}
