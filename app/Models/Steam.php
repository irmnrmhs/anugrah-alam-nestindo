<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Steam extends Model
{
    protected $fillable = [
        'products_id',
        // 'officers_id',
        'nests_id',
        'batch',
        'petugas',
        'penambahan',
        'sumber_panas',
        'tgl_pemanasan',
        'lv_air',
        'suhu_awal',
    ];

    public function nest()
    {
        return $this->belongsTo(NestType::class, 'nests_id');
    }

    // public function officer()
    // {
    //     return $this->belongsTo(SteamOfficer::class, 'officers_id');
    // }

    public function product()
    {
        return $this->belongsTo(Product::class, 'products_id');
    }

    public function fproduct()
    {
        return $this->hasOne(FinishedProduct::class, 'steams_id');
    }

    // public function dsteams()
    // {
    //     return $this->hasMany(DetailSteam::class, 'steams_id');
    // }

    public function biji()
    {
        
    }

    public function berat()
    {
        
    }

    public function tray()
    {
        
    }
}
