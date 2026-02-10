<?php

namespace App\Observers;

use App\Models\Arrival;
use App\Models\RawMaterial;

class ArrivalObserver
{
    public function created(Arrival $arrival): void
    {
        RawMaterial::firstOrCreate(
            ['kode' => $arrival->rm_code],
            ['biji' => 0, 'berat' => 0]
        );
    }

    public function updated(Arrival $arrival): void
    {
        if ($arrival->wasChanged(['dcertificates_id', 'tgl_kedatangan'])) {

            $oldKode = $arrival->getOriginal('rm_code');

            $stillUsed = Arrival::all()
                ->filter(fn ($a) => $a->rm_code === $oldKode)
                ->count() > 0;

            if (!$stillUsed) {
                RawMaterial::where('kode', $oldKode)
                    ->update(['kode' => $arrival->rm_code]);
            }
        }
    }

    public function deleting(Arrival $arrival): void
    {
        $rawUsedWithValue = RawMaterial::where('kode', $arrival->rm_code)
            ->where(fn ($q) =>
                $q->where('biji', '>', 0)
                  ->orWhere('berat', '>', 0)
            )
            ->exists();

        if ($rawUsedWithValue) {
            throw new \Exception(
                'Gagal hapus. Kode bahan baku sudah diproses.'
            );
        }
    }

    public function deleted(Arrival $arrival): void
    {
        $stillUsed = Arrival::all()
            ->filter(fn ($a) => $a->rm_code === $arrival->rm_code)
            ->count() > 0;

        if (!$stillUsed) {
            RawMaterial::where('kode', $arrival->rm_code)->delete();
        }
    }
}
