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

    public function arrivals()
    {
        return $this->hasMany(Arrival::class, 'kode', 'kode');
    }
    
    public function rmResults()
    {
        return $this->hasMany(RmResult::class, 'rms_id');
    }
    
    public function rmAlums()
    {
        return $this->hasMany(RmAlum::class, 'rms_id');
    }
    
    public function ccp1()
    {
        return $this->hasMany(Ccp1::class, 'rms_id');
    }
    
    public function ccpAlums()
    {
        return $this->hasMany(CcpAlum::class, 'rms_id');
    }

    public function stocks()
    {
        return $this->hasMany(RmStock::class, 'rms_id');
    }

    public function identifiers()
    {
        return $this->hasMany(ProductIdentifier::class, 'rms_id');
    }

    public function shapes()
    {
        return $this->hasMany(GradeShape::class, 'rms_id');
    }

    public function feathers()
    {
        return $this->hasMany(GradeFeather::class, 'rms_id');
    }

    public function colors()
    {
        return $this->hasMany(GradeColor::class, 'rms_id');
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

    public function reduceStock($biji, $berat)
    {
        if ($biji > $this->biji_sisa || $berat > $this->berat_sisa) {
            throw new \Exception('Melebihi stok sisa');
        }

        $this->decrement('biji_sisa', $biji);
        $this->decrement('berat_sisa', $berat);
    }

    public function returnStock($biji, $berat)
    {
        $this->increment('biji_sisa', $biji);
        $this->increment('berat_sisa', $berat);
    }

    public function getTotalBijiRmAttribute()
    {
        return $this->stocks()->sum('biji_keluar');
    }

    public function getTotalBeratRmAttribute()
    {
        return $this->stocks()->sum('berat_keluar');
    }

    // Product Identifier Sisa
    public function getBijiSisaIdentifierAttribute(){
        return $this->total_biji_rm - $this->identifiers()->sum('biji');
    }

    public function getBeratSisaIdentifierAttribute(){
        return $this->total_berat_rm - $this->identifiers()->sum('berat');
    }

    // Grade Shape Sisa
    public function getBeratSisaShapeAttribute(){
        return $this->total_berat_rm - $this->shapes()->sum('berat');
    }

    // Grade Feather Sisa
    public function getBeratSisaFeatherAttribute(){
        return $this->total_berat_rm - $this->feathers()->sum('biji');
    }

    public function getBijiSisaFeatherAttribute(){
        return $this->total_biji_rm - $this->feathers()->sum('berat');
    }

    // Grade Color Sisa
    public function getBeratSisaColorAttribute(){
        return $this->total_berat_rm - $this->colors()->sum('biji');
    }

    public function getBijiSisaColorAttribute(){
        return $this->total_biji_rm - $this->colors()->sum('berat');
    }
}
