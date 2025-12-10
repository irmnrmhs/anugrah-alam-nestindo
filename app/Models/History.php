<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $fillable = [
        'identifiers_id',
        'asal',
        'tujuan',
        'biji',
        'berat'
    ];

    public function identifier()
    {
        return $this->belongsTo(ProductIdentifier::class, 'identifiers_id');
    }

    public function edges()
    {
        return $this->hasMany(Edge::class, 'histories_id');
    }

    public function washes()
    {
        return $this->hasMany(Wash::class, 'histories_id');
    }

    public function corrections()
    {
        return $this->hasMany(Correction::class, 'histories_id');
    }

    // 1. Sesek Kaki
    public function getTotalBijiSesekAttribute()
    {
        return $this->edges()->sum('biji_masuk');
    }

    public function getTotalBeratSesekAttribute()
    {
        return $this->edges()->sum('berat_masuk');
    }

    public function getSisaBijiSesekAttribute()
    {
        return $this->biji - $this->total_biji_sesek;
    }

    public function getSisaBeratSesekAttribute()
    {
        return $this->berat - $this->total_berat_sesek;
    }

    // 2. Pencucian
    public function getTotalBijiCuciAttribute()
    {
        return $this->washes()->sum('biji_masuk');
    }

    public function getTotalBeratCuciAttribute()
    {
        return $this->washes()->sum('berat_masuk');
    }

    public function getSisaBijiCuciAttribute()
    {
        return $this->biji - $this->total_biji_cuci;
    }

    public function getSisaBeratCuciAttribute()
    {
        return $this->berat - $this->total_berat_cuci;
    }

    // 3. Inspeksi dan Koreksi
    public function getTotalBijiKoreksiAttribute()
    {
        return $this->corrections()->sum('biji_masuk');
    }

    public function getTotalBeratKoreksiAttribute()
    {
        return $this->corrections()->sum('berat_masuk');
    }

    public function getSisaBijiKoreksiAttribute()
    {
        return $this->biji - $this->total_biji_koreksi;
    }

    public function getSisaBeratKoreksiAttribute()
    {
        return $this->berat - $this->total_berat_koreksi;
    }
}
