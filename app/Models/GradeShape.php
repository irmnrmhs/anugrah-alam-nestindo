<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeShape extends Model
{
    protected $fillable = [
        'rms_id',
        'employees_id',
        'shapes_id',
        'berat',
        'tanggal',
    ];

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class, 'rms_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employees_id');
    }

    public function shape()
    {
        return $this->belongsTo(Shape::class, 'shapes_id');
    }

    // public function mk()
    // {
    //     $mk = $this->shape()->where('kode', 'mk')->get();
    //     return $mk;
    // }

    // public function ovl()
    // {
    //     $ovl = $this->shape()->where('kode', 'ovl')->get();
    //     return $ovl;
    // }

    // public function sdt()
    // {
    //     $sdt = $this->shape()->where('kode', 'sdt')->get();
    //     return $sdt;
    // }

    // public function pth()
    // {
    //     $pth = $this->shape()->where('kode', 'pth')->get();
    //     return $pth;
    // }

    // public function hcr()
    // {
    //     $hcr = $this->shape()->where('kode', 'hcr')->get();
    //     return $hcr;
    // }

    // public function isMK(): bool
    // {
    //     return $this->shape?->kode === 'mk';
    // }

}
