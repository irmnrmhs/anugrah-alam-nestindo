<?php

namespace App\Observers;

use App\Models\Steam;
use App\Models\FinishedProduct;
use Illuminate\Validation\ValidationException;

class SteamObserver
{
    /**
     * Handle the Steam "created" event.
     */
    public function created(Steam $steam): void
    {
        FinishedProduct::create([
            'batch' => $steam->batch,
            'steams_id' => $steam->id,
            'biji' => 0,
            'berat' => 0,
            'tanggal' => $steam->tgl_pemanasan,
        ]);
    }

    public function updating(Steam $steam)
    {
        //
    }
    /**
     * Handle the Steam "updated" event.
     */
    public function updated(Steam $steam): void
    {
        //
    }

    public function deleting(Steam $steam): void
    {
        $fp = FinishedProduct::where('steams_id', $steam->id)->first();

        if (!$fp) return;

        $fp->delete();
    }

    /**
     * Handle the Steam "deleted" event.
     */
    public function deleted(Steam $steam): void
    {
        //
    }

    /**
     * Handle the Steam "restored" event.
     */
    public function restored(Steam $steam): void
    {
        //
    }

    /**
     * Handle the Steam "force deleted" event.
     */
    public function forceDeleted(Steam $steam): void
    {
        //
    }
}
