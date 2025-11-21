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
        // otomatis membuat RawMaterial baru berdasarkan kode arrival
        RawMaterial::create([
            'kode' => $arrival->kode,
            'biji' => 0,
            'berat' => 0,
        ]);
    }

    /**
     * Handle the Arrival "updated" event.
     */
    public function updated(Arrival $arrival): void
    {
        // cek apakah arrival.kod e berubah
        if ($arrival->wasChanged('kode')) {

            $oldCode = $arrival->getOriginal('kode'); // kode sebelum update
            $newCode = $arrival->kode;               // kode arrival terbaru

            // update raw material yang menggunakan kode arrival lama
            RawMaterial::where('kode', $oldCode)
                ->update([
                    'kode' => $newCode
                ]);
        }
    }

    /**
     * Handle the Arrival "deleted" event.
     */
    public function deleted(Arrival $arrival): void
    {
        //
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
