<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dcertificate extends Model
{
    protected $fillable = [
        'companies_id',
        'suppliers_id',
        'wbhouses_id',
        'no_skp',
        'tgl_skp'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'companies_id');
    }
    
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'suppliers_id');
    }

    public function wbhouse()
    {
        return $this->belongsTo(WBHouse::class, 'wbhouses_id');
    }

    public function arrival()
    {
        return $this->hasOne(Arrival::class, 'dcertificates_id');
    }

    public function details()
    {
        return $this->hasMany(DetailSkp::class, 'dcertificates_id');
    }
}