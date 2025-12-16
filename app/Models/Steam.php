<?php

namespace App\Models;

use App\Http\Controllers\FinishedController;
use Illuminate\Database\Eloquent\Model;

class Steam extends Model
{
    protected $fillable = [
        'fproducts_id',
        'officers_id',
        'nests_id',
        'penambahan',
        'sumber_panas',
        'kode',
        'standar',
        'tgl_pemanasan',
        'suhu_awal',
        'biji',
        'berat',
        'suhu',
        'waktu',
        'suhu_total',
        'waktu_total',
        'jml_tray',
        'keterangan'
    ];

    public function officer()
    {
        return $this->belongsTo(SteamOfficer::class, 'officers_id');
    }

    public function fproduct()
    {
        return $this->belongsTo(FinishedProduct::class, 'fproducts_id');
    }

    public function nest()
    {
        return $this->belongsTo(NestType::class, 'nests_id');
    }
}
