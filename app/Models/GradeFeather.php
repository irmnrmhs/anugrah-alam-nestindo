<?php

namespace App\Models;

use App\Models\Employee;
use App\Models\Feather;
use App\Models\RawMaterial;
use Illuminate\Database\Eloquent\Model;

class GradeFeather extends Model
{
    protected $fillable = [
        'rms_id',
        'employees_id',
        'feathers_id',
        'biji',
        'berat',
        'tanggal'
    ];

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class, 'rms_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employees_id');
    }

    public function feather()
    {
        return $this->belongsTo(Feather::class, 'feathers_id');
    }

    public function brp()
    {
        $brp = $this->feather()->where('kode', 'brp')->get();
        return $brp;
    }

    public function bs()
    {
        $bs = $this->feather()->where('kode', 'bs')->get();
        return $bs;
    }

    public function bb()
    {
        $bb = $this->feather()->where('kode', 'bb')->get();
        return $bb;
    }
}
