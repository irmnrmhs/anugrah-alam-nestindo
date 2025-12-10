<?php

namespace App\Observers;

use App\Models\Correction;
use App\Models\History;

class CorrectionObserver
{
    /**
     * Handle the Correction "created" event.
     */
    public function created(Correction $correction): void
    {
        History::create([
            'identifiers_id' => $correction->history->identifiers_id,
            'asal' => 'PR04IK',
            'tujuan' => 'PR05PB',
            'biji' => $correction->biji_keluar,
            'berat' => $correction->berat_keluar,
            'status' => 0
        ]);
    }

    /**
     * Handle the Correction "updated" event.
     */
    public function updated(Correction $correction): void
    {
        //
    }

    /**
     * Handle the Correction "deleted" event.
     */
    public function deleted(Correction $correction): void
    {
        //
    }

    /**
     * Handle the Correction "restored" event.
     */
    public function restored(Correction $correction): void
    {
        //
    }

    /**
     * Handle the Correction "force deleted" event.
     */
    public function forceDeleted(Correction $correction): void
    {
        //
    }
}
