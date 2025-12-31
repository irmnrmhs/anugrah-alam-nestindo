<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductIdentifier extends Model
{
    protected $fillable = [
        'rms_id',
        'grades_id',
        'kode',
        'tanggal',
        'biji',
        'berat'
    ];

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class, 'rms_id');
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grades_id');
    }

    public function histories()
    {
        return $this->hasMany(History::class, 'identifiers_id');
    }

    // public function getTotalBijiKeluarAttribute()
    // {
    //     return $this->sum('biji');
    // }

    // public function getTotalBeratKeluarAttribute()
    // {
    //     return $this->sum('berat');
    // }

    // public function getBijiSisaAttribute(){
    //     return $this->rawMaterial()->biji - $this->rawMaterial()->biji_sisa - $this->total_biji_keluar;
    // }

    // public function getBeratSisaAttribute(){
    //     return $this->rawMaterial()->berat - $this->rawMaterial()->berat_sisa - $this->total_berat_keluar;
    // }

    // public function getBijiSisaAttribute()
    // {
    //     return $this->biji < $this->rawMaterial()->biji_sisa_identifier;
    // }

    // public function getBeratSisaAttribute()
    // {
    //     return $this->berat < $this->rawMaterial()->berat_sisa_identifier;
    // }
}
