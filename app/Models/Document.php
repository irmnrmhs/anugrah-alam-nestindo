<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'employees_id',
        'depts_id',
        'kode',
        'no',
        'name',
        'rev'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employees_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'depts_id');
    }

    public function getRevFormattedAttribute()
    {
        return str_pad($this->rev, 2, '0', STR_PAD_LEFT);
    }

}
