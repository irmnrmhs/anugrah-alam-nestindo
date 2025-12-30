<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $fillable = [
        'posisi',
        'keterangan'
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class, 'positions_id');
    }
}
