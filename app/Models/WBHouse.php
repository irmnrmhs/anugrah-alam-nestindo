<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Area;

class WBHouse extends Model
{
    protected $fillable = [
        'kode',
        'nama',
        'alamat',
        'areas_id',
        'kapasitas',
        'owner',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class, 'areas_id');
    }

    public function dcertificates()
    {
        return $this->hasMany(Dcertificate::class, 'wbhouses_id');
    }

    // protected static function booted()
    // {
    //     static::updated(function ($wbhouse) {

    //         // jalankan hanya jika kolom 'kode' berubah
    //         if ($wbhouse->wasChanged('kode')) {

    //             // ambil semua SKP (dcertificates) yang terhubung
    //             $dcertIds = $wbhouse->dcertificates()->pluck('id');

    //             if ($dcertIds->count() === 0) {
    //                 return; // tidak ada data arrival yang perlu diupdate
    //             }

    //             // update semua arrival yang memakai dcertificates tersebut
    //             \App\Models\Arrival::whereIn('dcertificates_id', $dcertIds)
    //                 ->chunkById(100, function ($arrivals) use ($wbhouse) {
    //                     foreach ($arrivals as $arrival) {
    //                         $tgl = date('dmy', strtotime($arrival->tgl_kedatangan));
    //                         $arrival->update([
    //                             'kode' => $wbhouse->kode . '-' . $tgl
    //                         ]);
    //                     }
    //                 });
    //         }
    //     });
    // }
}
