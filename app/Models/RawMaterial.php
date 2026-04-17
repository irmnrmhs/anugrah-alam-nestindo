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
    ];

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
        return $this->stocks()->sum('biji');
    }

    // total berat keluar
    public function getTotalBeratKeluarAttribute()
    {
        return $this->stocks()->sum('berat');
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

    // Product Identifier Sisa
    public function getBijiSisaIdentifierAttribute(){
        return $this->total_biji_keluar - $this->identifiers()->sum('biji');
    }

    public function getBeratSisaIdentifierAttribute(){
        return $this->total_berat_keluar - $this->identifiers()->sum('berat');
    }

    // Total Berat Grade Bentuk
    public function getTotalBeratShapeAttribute(){
        return $this->shapes()->sum('berat');
    }

    // Total Berat Grade Bulu
    public function getTotalBeratFeatherAttribute(){
        return $this->feathers()->sum('berat');
    }

    // Total Biji Grade Bulu
    public function getTotalBijiFeatherAttribute(){
        return $this->feathers()->sum('biji');
    }

    // Total Berat Grade Color
    public function getTotalBeratColorAttribute(){
        return $this->colors()->sum('berat');
    }

    // Total Biji Grade Color
    public function getTotalBijiColorAttribute(){
        return $this->colors()->sum('biji');
    }

    // Grade Shape Sisa
    public function getBeratSisaShapeAttribute(){
        return $this->total_berat_keluar - $this->total_berat_shape;
    }

    // Grade Feather Sisa
    public function getBeratSisaFeatherAttribute(){
        return $this->total_berat_shape - $this->total_berat_feather;
    }

    public function getBijiSisaFeatherAttribute(){
        return $this->total_biji_keluar - $this->total_biji_feather;
    }

    public function getBeratSisaColorAttribute(){
        return $this->total_berat_feather - $this->total_berat_color;
    }

    public function getBijiSisaColorAttribute(){
        return $this->total_biji_feather - $this->total_biji_color;
    }

    public function getRbwAttribute()
    {
        $arrival = $this->arrivals->first();
        return optional(optional($arrival?->dcertificate)?->wbhouse)?->nama . ' / ' . optional(optional($arrival?->dcertificate)?->wbhouse)?->kode;
    }
}
