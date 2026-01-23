<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'employees_id',
        'steps_id',
        'kode',
        'no',
        'name',
        'rev'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employees_id');
    }

    public function step()
    {
        return $this->belongsTo(Step::class, 'steps_id');
    }

    public function getRevFormattedAttribute()
    {
        return str_pad($this->rev, 2, '0', STR_PAD_LEFT);
    }

}
