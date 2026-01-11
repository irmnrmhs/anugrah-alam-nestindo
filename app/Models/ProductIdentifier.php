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

    public static function generateKode($rmKode, $gradeKode)
    {
        return preg_replace('/[^A-Za-z0-9]/', '', $rmKode)
            . '-' .
            preg_replace('/[^A-Za-z0-9]/', '', $gradeKode);
    }
}
