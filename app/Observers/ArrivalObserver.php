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
            ['kode' => $arrival->kode],
            ['biji' => 0, 'berat' => 0]
        );
    }

    /**
     * Handle the Arrival "updated" event.
     */
    public function updated(Arrival $arrival): void
    {
        if ($arrival->wasChanged('kode')) {

            $oldKode = $arrival->getOriginal('kode');

            $stillUsed = Arrival::where('kode', $oldKode)->exists();

            if (!$stillUsed) {
                RawMaterial::where('kode', $oldKode)
                    ->update(['kode' => $arrival->kode]);
            }
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
