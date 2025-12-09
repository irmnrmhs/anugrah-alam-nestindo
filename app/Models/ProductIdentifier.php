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

    /**
     * Total biji yang sudah dipakai oleh semua identifier untuk RM ini (termasuk current record).
     */
    public function totalBijiUsedOnRm(): int
    {
        return (int) self::where('rms_id', $this->rms_id)->sum('biji');
    }

    /**
     * Total berat yang sudah dipakai oleh semua identifier untuk RM ini.
     */
    public function totalBeratUsedOnRm(): float
    {
        return (float) self::where('rms_id', $this->rms_id)->sum('berat');
    }

    /**
     * Available biji from raw material AFTER accounting rm_stocks (rawMaterial->biji_sisa)
     * minus other identifiers (excluding optionally this id)
     */
    public static function availableBijiForRm(int $rms_id, int $excludeIdentifierId = null): int
    {
        $rm = RawMaterial::with('stocks')->find($rms_id);
        if (! $rm) return 0;

        // sisa dari raw material berdasarkan rm_stocks (biji - total_biji_keluar)
        $availableFromRaw = $rm->biji_sisa;

        // jumlah biji yang sudah digunakan oleh identifiers untuk RM ini
        $usedQuery = self::where('rms_id', $rms_id);
        if ($excludeIdentifierId) {
            $usedQuery->where('id', '!=', $excludeIdentifierId);
        }
        $used = (int) $usedQuery->sum('biji');

        return max(0, $availableFromRaw - $used);
    }

    public static function availableBeratForRm(int $rms_id, int $excludeIdentifierId = null): float
    {
        $rm = RawMaterial::with('stocks')->find($rms_id);
        if (! $rm) return 0.0;

        $availableFromRaw = $rm->berat_sisa;

        $usedQuery = self::where('rms_id', $rms_id);
        if ($excludeIdentifierId) {
            $usedQuery->where('id', '!=', $excludeIdentifierId);
        }
        $used = (float) $usedQuery->sum('berat');

        // return positive value
        return max(0.0, $availableFromRaw - $used);
    }

    /**
     * Convenient accessor: sisa biji untuk THIS identifier context (available before creating this identifier).
     * Note: this is not a persisted column; computed on the fly.
     */
    public function getSisaBijiAttribute()
    {
        // available for rm excluding this identifier (important when editing)
        return self::availableBijiForRm($this->rms_id, $this->id ?? null);
    }

    public function getSisaBeratAttribute()
    {
        return self::availableBeratForRm($this->rms_id, $this->id ?? null);
    }

//     public function getStokBijiAttribute()
//     {
//         return $this->rawMaterial()->sum('total_biji_keluar') - $this->biji;
//     }

//     public function getStokBeratAttribute()
//     {
//         return $this->rawMaterial()->sum('total_berat_keluar') - $this->berat;
//     }

//     public function getSisaBijiAttribute()
//     {
//         return $this->stok_biji - $this->biji;
//     }

//     public function getSisaBeratAttribute()
//     {
//         return $this->stok_berat - $this->berat;
//     }
}
