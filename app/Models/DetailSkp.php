<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailSkp extends Model
{
    protected $fillable = [
        'dcertificates_id',
        'tgl_panen',
        'berat_panen',
        'tgl_kirim',
        'berat_kirim'
    ];

    public function dcertificate()
    {
        return $this->belongsTo(Dcertificate::class, 'dcertificates_id');
    }
}
