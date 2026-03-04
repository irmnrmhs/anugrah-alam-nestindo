<?php

namespace App\Models;

use App\Http\Controllers\FpResultController;
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

    public function steams()
    {
        return $this->hasMany(Steam::class, 'fproducts_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'batch_id');
    }

    // Finished Product
    public function getTotalBijiKeluarAttribute()
    {
        return $this->fpstocks()->sum('biji_keluar');
    }

    public function getTotalBeratKeluarAttribute()
    {
        return $this->fpstocks()->sum('berat_keluar');
    }

    public function getBijiSisaAttribute()
    {
        return $this->biji - $this->total_biji_keluar;
    }

    public function getBeratSisaAttribute()
    {
        return $this->berat - $this->total_berat_keluar;
    }

    public function fpResults()
    {
        return $this->hasMany(FpResult::class, 'products_id');
    }
}
