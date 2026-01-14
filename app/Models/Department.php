<?php

namespace App\Models;

use Dom\Document;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'kd_dept',
        'nama_dept',
        'deskripsi',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class, 'id_dept');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'depts_id');
    }
}