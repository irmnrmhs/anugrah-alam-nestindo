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

    public function picks()
    {
        return $this->hasMany(Pick::class, 'histories_id');
    }

    public function soaks()
    {
        return $this->hasMany(Soak::class, 'histories_id');
    }

    public function rinses()
    {
        return $this->hasMany(Rinse::class, 'histories_id');
    }

    public function entries()
    {
        return $this->hasMany(Entry::class, 'histories_id');
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

    // 4. Pencabutan Bulu
    public function getTotalBijiCabutAttribute()
    {
        return $this->corrections()->sum('biji_masuk');
    }

    public function getTotalBeratCabutAttribute()
    {
        return $this->corrections()->sum('berat_masuk');
    }

    public function getSisaBijiCabutAttribute()
    {
        return $this->biji - $this->total_biji_cb;
    }

    public function getSisaBeratCabutAttribute()
    {
        return $this->berat - $this->total_berat_cb;
    }

    // 5. Perendaman
    public function getTotalBijiRendamAttribute()
    {
        return $this->corrections()->sum('biji_masuk');
    }

    public function getTotalBeratRendamAttribute()
    {
        return $this->corrections()->sum('berat_masuk');
    }

    public function getSisaBijiRendamAttribute()
    {
        return $this->biji - $this->total_biji_rendam;
    }

    public function getSisaBeratRendamAttribute()
    {
        return $this->berat - $this->total_berat_rendam;
    }

    // 6. Cabut Bilas
    public function getTotalBijiBilasAttribute()
    {
        return $this->corrections()->sum('biji_masuk');
    }

    public function getTotalBeratBilasAttribute()
    {
        return $this->corrections()->sum('berat_masuk');
    }

    public function getSisaBijiBilasAttribute()
    {
        return $this->biji - $this->total_biji_bilas;
    }

    public function getSisaBeratBilasAttribute()
    {
        return $this->berat - $this->total_berat_bilas;
    }

    // 7. Cetak Masuk
    public function getTotalBijiEntryAttribute()
    {
        return $this->corrections()->sum('biji_masuk');
    }

    public function getTotalBeratEntryAttribute()
    {
        return $this->corrections()->sum('berat_masuk');
    }

    public function getSisaBijiEntryAttribute()
    {
        return $this->biji - $this->total_biji_entry;
    }

    public function getSisaBeratEntryAttribute()
    {
        return $this->berat - $this->total_berat_entry;
    }
}
