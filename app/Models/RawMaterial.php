<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    protected $fillable = [
        'kode',
        'arrivals_id',
        'biji',
        'berat',
        // 'kadar_air'
    ];
    
    // public function containers()
    // {
    //     return $this->hasMany(Container::class, 'raw_materials_id');
    // }

    public function arrival()
    {
        return $this->belongsTo(Arrival::class, 'arrivals_id');
    }
    
    public function rmResults()
    {
        return $this->hasMany(RmResult::class, 'rms_id');
    }

    public function stocks()
    {
        return $this->hasMany(RmStock::class, 'rms_id');
    }

    public function identifiers()
    {
        return $this->hasMany(ProductIdentifier::class, 'rms_id');
    }

    // total biji keluar
    public function getTotalBijiKeluarAttribute()
    {
        return $this->stocks()->sum('biji_keluar');
    }

    // total berat keluar
    public function getTotalBeratKeluarAttribute()
    {
        return $this->stocks()->sum('berat_keluar');
    }

    // biji sisa
    public function getBijiSisaAttribute()
    {
        return $this->biji - $this->total_biji_keluar;
    }

    // berat sisa
    public function getBeratSisaAttribute()
    {
        return $this->berat - $this->total_berat_keluar;
    }

    // public function getTotalDipakaiBijiAttribute()
    // {
    //     return $this->productIdentifiers->sum('biji');
    // }

    // public function getTotalDipakaiBeratAttribute()
    // {
    //     return $this->productIdentifiers->sum('berat');
    // }

    // /**
    //  * Sisa stok yang boleh dipakai ProductIdentifier
    //  */
    // public function getSisaUntukIdentifikasiBijiAttribute()
    // {
    //     return $this->total_keluar_biji - $this->total_dipakai_biji;
    // }

    // public function getSisaUntukIdentifikasiBeratAttribute()
    // {
    //     return $this->total_keluar_berat - $this->total_dipakai_berat;
    // }
}
