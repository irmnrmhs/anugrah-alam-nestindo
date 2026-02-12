<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeFeather extends Model
{
    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class, 'rms_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employees_id');
    }
}
