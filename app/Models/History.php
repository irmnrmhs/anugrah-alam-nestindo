<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $fillable = [
        'gcolors_id',
        'asal',
        'tujuan',
        'biji',
        'berat',
    ];

    public function identifier()
    {
        return $this->belongsTo(ProductIdentifier::class, 'identifiers_id');
    }

    public function gcolor()
    {
        return $this->belongsTo(GradeColor::class, 'gcolors_id');
    }

    public function edges()
    {
        return $this->hasMany(Edge::class, 'histories_id');
    }

    public function washes()
    {
        return $this->hasMany(Wash::class, 'histories_id');
    }

    public function corrections()
    {
        return $this->hasMany(Correction::class, 'histories_id');
    }

    public function picks()
    {
        return $this->hasMany(Pick::class, 'histories_id');
    }

    public function soaks()
    {
        return $this->hasMany(Soak::class, 'histories_id');
    }

    public function rinses()
    {
        return $this->hasMany(Rinse::class, 'histories_id');
    }

    public function entries()
    {
        return $this->hasMany(Entry::class, 'histories_id');
    }
    
    public function pulls()
    {
        return $this->hasMany(Pull::class, 'histories_id');
    }

    public function dries()
    {
        return $this->hasMany(Dry::class, 'histories_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'histories_id');
    }

    public function getTotalHancuranAttribute()
    {
        return $this->gcolor()->sum('other');
    }

    // 1. Sesek Kaki
    public function getTotalBijiSesekAttribute()
    {
        return $this->edges()->sum('biji');
    }

    public function getHancuranSesekAttribute()
    {
        return $this->edges()->sum('hancuran');
    }

    public function getSisaBijiSesekAttribute()
    {
        return $this->biji - $this->total_biji_sesek;
    }

    // 2. Pencucian
    public function getTotalBijiCuciAttribute()
    {
        return $this->washes()->sum('biji_out');
    }

    public function getSisaBijiCuciAttribute()
    {
        return $this->biji - $this->total_biji_cuci;
    }

    // 3. Inspeksi dan Koreksi
    public function getTotalBijiKoreksiAttribute()
    {
        return $this->corrections()->sum('biji');
    }

    public function getSisaBijiKoreksiAttribute()
    {
        return $this->biji - $this->total_biji_koreksi;
    }

    // 4. Pencabutan Bulu
    public function getTotalBijiCabutAttribute()
    {
        return $this->picks()->sum('biji');
    }

    public function getSisaBijiCabutAttribute()
    {
        return $this->biji - $this->total_biji_cabut;
    }

    // 5. Perendaman
    public function getTotalBijiRendamAttribute()
    {
        return $this->soaks()->sum('biji');
    }

    public function getSisaBijiRendamAttribute()
    {
        return $this->biji - $this->total_biji_rendam;
    }

    // 6. Cabut Bilas
    public function getTotalBijiBilasAttribute()
    {
        return $this->rinses()->sum('biji');
    }

    public function getSisaBijiBilasAttribute()
    {
        return $this->biji - $this->total_biji_bilas;
    }

    // 7. Cetak Masuk
    public function getTotalBijiEntryAttribute()
    {
        return $this->entries()->sum('biji');
    }

    public function getSisaBijiEntryAttribute()
    {
        return $this->biji - $this->total_biji_entry;
    }

    // 7. Cetak Keluar
    public function getTotalBijiKeluarAttribute()
    {
        return $this->pulls()->sum('biji');
    }

    public function getSisaBijiKeluarAttribute()
    {
        return $this->biji - $this->total_biji_keluar;
    }

    // 8. Pengeringan
    public function getTotalBijiKeringAttribute()
    {
        return $this->dries()->sum('biji');
    }

    public function getSisaBijiKeringAttribute()
    {
        return $this->biji - $this->total_biji_kering;
    }

    // 9. Grading Produk Jadi
    public function getTotalBijiProdukAttribute()
    {
        return $this->products()->sum('biji');
    }

    public function getTotalBeratProdukAttribute()
    {
        return $this->products()->sum('berat');
    }

    public function getSisaBijiProdukAttribute()
    {
        return $this->biji - $this->total_biji_produk;
    }

    public function getSisaBeratProdukAttribute()
    {
        return $this->berat - $this->total_berat_produk;
    }

    public function getRbwAttribute()
    {
        return $this->gcolor->rawMaterial->arrivals->first()->dcertificate->wbhouse->nama . ' / ' . $this->gcolor->rawMaterial->arrivals->first()->dcertificate->wbhouse->kode;
    }

    public function getRmAttribute()
    {
        return $this->gcolor->rawMaterial->kode;
    }

    public function getGradeAttribute()
    {
        return $this->gcolor->grade;
    }
}
