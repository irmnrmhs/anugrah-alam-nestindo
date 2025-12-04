<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'nip',
        'nama',
        'dept_id',
    ];

    // public function user()
    // {
    //     return $this->hasOne();
    // } 

    public function department()
    {
        return $this->belongsTo(Department::class, 'dept_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'employees_id');
    }
    
    public function arrival()
    {
        return $this->hasOne(Arrival::class, 'employees_id');
    }

    public function containers()
    {
        return $this->hasMany(Container::class, 'employees_id');
    }

    public function stocks()
    {
        return $this->hasMany(RmStock::class, 'rms_id');
    }

    public function edges()
    {
        return $this->hasMany(Edge::class, 'employees_id');
    }
}
