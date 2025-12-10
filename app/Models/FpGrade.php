<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FpGrade extends Model
{
    protected $fillable = [
        'grade',
        'keterangan',
        'status'
    ];
}
