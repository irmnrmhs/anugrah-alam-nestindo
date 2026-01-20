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

    public static function generateKodeFromRawMaterial(
        RawMaterial $rm,
        Grade $grade
    ): string {
        $cleanGrade = preg_replace('/[^A-Za-z0-9]/', '', $grade->grade);
        $cleanRm    = preg_replace('/[^A-Za-z0-9]/', '', $rm->kode);

        $arrival = $rm->arrivals()
            ->with('dcertificate.supplier')
            ->latest('tgl_kedatangan')
            ->first();

        $supplierKode = $arrival?->dcertificate?->supplier?->kode ?? '';

        return $supplierKode
            ? "{$cleanGrade}-{$cleanRm}{$supplierKode}"
            : "{$cleanGrade}-{$cleanRm}";
    }
}
