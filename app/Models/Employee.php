<?php

namespace App\Models;

use App\Http\Controllers\SteamOfficerController;
use Complex\Functions;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'nip',
        'nama',
        'positions_id',
        'dept_id',
        'status'
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

    public function gfeathers()
    {
        return $this->hasMany(GradeFeather::class, 'employees_id');
    }

    public function gcolors()
    {
        return $this->hasMany(GradeColor::class, 'employees_id');
    }

    public function edges()
    {
        return $this->hasMany(Edge::class, 'employees_id');
    }

    public function washes()
    {
        return $this->hasMany(Wash::class, 'employees_id');
    }

    public function corrections()
    {
        return $this->hasMany(Correction::class, 'employees_id');
    }

    public function picks()
    {
        return $this->hasMany(Pick::class, 'employees_id');
    }

    public function soaks()
    {
        return $this->hasMany(Soak::class, 'employees_id');
    }

    public function rinses()
    {
        return $this->hasMany(Rinse::class, 'employees_id');
    }

    public function entries()
    {
        return $this->hasMany(Entry::class, 'employees_id');
    }
    
    public function pulls()
    {
        return $this->hasMany(Pull::class, 'employees_id');
    }

    public function dries()
    {
        return $this->hasMany(Dry::class, 'employees_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'employees_id');
    }
    
    public function fpstocks()
    {
        return $this->hasMany(FinishedProduct::class, 'employees_id');
    }

    public function officer()
    {
        return $this->hasOne(SteamOfficer::class, 'employees_id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'positions_id');
    }

    public function steps()
    {
        return $this->hasMany(Step::class, 'steps_id');
    }
}
