<?php

namespace App\Observers;

use App\Models\Arrival;
use App\Models\RawMaterial;

class ArrivalObserver
{
    /**
     * Handle the Arrival "created" event.
     */
    public function created(Arrival $arrival): void
    {
        RawMaterial::firstOrCreate(
            ['kode' => $arrival->rm_code],
            ['biji' => 0, 'berat' => 0]
        );
    }

    /**
     * Handle the Arrival "updated" event.
     */
    public function updated(Arrival $arrival): void
    {
        if ($arrival->wasChanged('rm_code')) {

            $oldKode = $arrival->getOriginal('rm_code');

            $stillUsed = Arrival::where('rm_code', $oldKode)->exists();

            if (!$stillUsed) {
                RawMaterial::where('rm_code', $oldKode)
                    ->update(['rm_code' => $arrival->rm_code]);
            }
        }
    }

    public function deleting(Arrival $arrival): void
    {
        $rawUsedWithValue = RawMaterial::where('kode', $arrival->kode)
            ->where(function ($q) {
                $q->where('biji', '>', 0)
                  ->orWhere('berat', '>', 0);
            })
            ->exists();

        if ($rawUsedWithValue) {
            throw new \Exception(
                'Gagal hapus. Kode bahan baku sudah diproses.'
            );
        }
    }

    /**
     * Handle the Arrival "deleted" event.
     */
    public function deleted(Arrival $arrival): void
    {
        $stillUsed = Arrival::where('kode', $arrival->kode)->exists();

        if (!$stillUsed) {
            RawMaterial::where('kode', $arrival->kode)->delete();
        }
    }

    /**
     * Handle the Arrival "restored" event.
     */
    public function restored(Arrival $arrival): void
    {
        //
    }

    /**
     * Handle the Arrival "force deleted" event.
     */
    public function forceDeleted(Arrival $arrival): void
    {
        //
    }
}
