<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $fillable = [
        'identifiers_id',
        'asal',
        'tujuan',
        'biji',
        'berat'
    ];

    public function identifier()
    {
        return $this->belongsTo(ProductIdentifier::class, 'identifiers_id');
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

    // 1. Sesek Kaki
    public function getTotalBijiSesekAttribute()
    {
        return $this->edges()->sum('biji_masuk');
    }

    public function getTotalBeratSesekAttribute()
    {
        return $this->edges()->sum('berat_masuk');
    }

    public function getSisaBijiSesekAttribute()
    {
        return $this->biji - $this->total_biji_sesek;
    }

    public function getSisaBeratSesekAttribute()
    {
        return $this->berat - $this->total_berat_sesek;
    }

    public function getDiprosesBijiSesekAttribute()
    {
        return $this->diproses_biji -= $this->sisa_biji_sesek;
    }

    public function getDiprosesBeratSesekAttribute()
    {
        return $this->sisa_berat_sesek -= $this->diproses_berat;
    }

    // 2. Pencucian
    public function getTotalBijiCuciAttribute()
    {
        return $this->washes()->sum('biji_masuk');
    }

    public function getTotalBeratCuciAttribute()
    {
        return $this->washes()->sum('berat_masuk');
    }

    public function getSisaBijiCuciAttribute()
    {
        return $this->biji - $this->total_biji_cuci;
    }

    public function getSisaBeratCuciAttribute()
    {
        return $this->berat - $this->total_berat_cuci;
    }

    // 3. Inspeksi dan Koreksi
    public function getTotalBijiKoreksiAttribute()
    {
        return $this->corrections()->sum('biji_masuk');
    }

    public function getTotalBeratKoreksiAttribute()
    {
        return $this->corrections()->sum('berat_masuk');
    }

    public function getSisaBijiKoreksiAttribute()
    {
        return $this->biji - $this->total_biji_koreksi;
    }

    public function getSisaBeratKoreksiAttribute()
    {
        return $this->berat - $this->total_berat_koreksi;
    }

    // 4. Pencabutan Bulu
    public function getTotalBijiCabutAttribute()
    {
        return $this->picks()->sum('biji_masuk');
    }

    public function getTotalBeratCabutAttribute()
    {
        return $this->picks()->sum('berat_masuk');
    }

    public function getSisaBijiCabutAttribute()
    {
        return $this->biji - $this->total_biji_cabut;
    }

    public function getSisaBeratCabutAttribute()
    {
        return $this->berat - $this->total_berat_cabut;
    }

    // 5. Perendaman
    public function getTotalBijiRendamAttribute()
    {
        return $this->soaks()->sum('biji_masuk');
    }

    public function getTotalBeratRendamAttribute()
    {
        return $this->soaks()->sum('berat_masuk');
    }

    public function getSisaBijiRendamAttribute()
    {
        return $this->biji - $this->total_biji_rendam;
    }

    public function getSisaBeratRendamAttribute()
    {
        return $this->berat - $this->total_berat_rendam;
    }

    // 6. Cabut Bilas
    public function getTotalBijiBilasAttribute()
    {
        return $this->rinses()->sum('biji_masuk');
    }

    public function getTotalBeratBilasAttribute()
    {
        return $this->rinses()->sum('berat_masuk');
    }

    public function getSisaBijiBilasAttribute()
    {
        return $this->biji - $this->total_biji_bilas;
    }

    public function getSisaBeratBilasAttribute()
    {
        return $this->berat - $this->total_berat_bilas;
    }

    // 7. Cetak Masuk
    public function getTotalBijiEntryAttribute()
    {
        return $this->entries()->sum('biji_masuk');
    }

    public function getTotalBeratEntryAttribute()
    {
        return $this->entries()->sum('berat_masuk');
    }

    public function getSisaBijiEntryAttribute()
    {
        return $this->biji - $this->total_biji_entry;
    }

    public function getSisaBeratEntryAttribute()
    {
        return $this->berat - $this->total_berat_entry;
    }

    // 7. Cetak Keluar
    public function getTotalBijiKeluarAttribute()
    {
        return $this->pulls()->sum('biji_masuk');
    }

    public function getTotalBeratKeluarAttribute()
    {
        return $this->pulls()->sum('berat_masuk');
    }

    public function getSisaBijiKeluarAttribute()
    {
        return $this->biji - $this->total_biji_keluar;
    }

    public function getSisaBeratKeluarAttribute()
    {
        return $this->berat - $this->total_berat_keluar;
    }

    // 8. Pengeringan
    public function getTotalBijiKeringAttribute()
    {
        return $this->dries()->sum('biji_masuk');
    }

    public function getTotalBeratKeringAttribute()
    {
        return $this->dries()->sum('berat_masuk');
    }

    public function getSisaBijiKeringAttribute()
    {
        return $this->biji - $this->total_biji_kering;
    }

    public function getSisaBeratKeringAttribute()
    {
        return $this->berat - $this->total_berat_kering;
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
}
