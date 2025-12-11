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

    // Product Identifier Sisa
    public function getBijiSisaIdentifierAttribute(){
        return $this->total_biji_keluar - $this->identifiers()->sum('biji');
    }

    public function getBeratSisaIdentifierAttribute(){
        return $this->total_berat_keluar - $this->identifiers()->sum('berat');
    }
}
