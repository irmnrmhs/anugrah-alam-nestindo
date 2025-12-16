<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SteamOfficer extends Model
{
    protected $fillable = [
        'employees_id',
        'status'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employees_id');
    }

    public function steams()
    {
        return $this->hasMany(Steam::class, 'officers_id');
    }
}