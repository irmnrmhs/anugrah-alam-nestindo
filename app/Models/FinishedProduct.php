<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinishedProduct extends Model
{
    protected $fillable = [
        'kode',
        'products_id',
        'biji',
        'berat'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'products_id');
    }

    public function fpstocks()
    {
        return $this->hasMany(FpStock::class, 'fproducts_id');
    }

    // total biji keluar
    public function getTotalBijiKeluarAttribute()
    {
        return $this->fpstocks()->sum('biji_keluar');
    }

    // total berat keluar
    public function getTotalBeratKeluarAttribute()
    {
        return $this->fpstocks()->sum('berat_keluar');
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

}
