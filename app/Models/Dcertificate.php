<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dcertificate extends Model
{
    protected $fillable = [
        'companies_id',
        'wbhouses_id',
        'no_skp',
        'tgl_skp'
    ];

    public function getTotalBeratAttribute()
    {
        return number_format(
            $this->details()->sum('berat_kirim') * 1000,
            0,
            ',',
            '.'
        );
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'companies_id');
    }

    public function wbhouse()
    {
        return $this->belongsTo(WBHouse::class, 'wbhouses_id');
    }

    public function details()
    {
        return $this->hasMany(DetailSkp::class, 'dcertificates_id');
    }

    public function arrivals()
    {
        return $this->hasMany(Arrival::class, 'dcertificates_id');
    }
}