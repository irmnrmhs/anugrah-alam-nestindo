<?php

namespace App\Models;

use App\Http\Controllers\FpResultController;
use Illuminate\Database\Eloquent\Model;

class FinishedProduct extends Model
{
    protected $fillable = [
        'batch',
        'steams_id',
        'biji',
        'berat',
    ];

    public function steam()
    {
        return $this->belongsTo(Steam::class, 'steams_id');
    }

    public function fpstocks()
    {
        return $this->hasMany(FpStock::class, 'fproducts_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'batch_id');
    }

    public function certificates()
    {
        return $this->hasMany(AnalysisCertficate::class, 'fproducts_id');
    }

    public function uploads()
    {
        return $this->hasMany(FinishedProduct::class, 'fproducts_id');
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
