<?php

namespace App\Observers;

use App\Models\Soak;
use App\Models\History;

class SoakObserver
{
    /**
     * Handle the Soak "created" event.
     */
    public function created(Soak $soak): void
    {
        History::create([
            'identifiers_id' => $soak->history->identifiers_id,
            'asal' => 'PR06PR',
            'tujuan' => 'PR07CB',
            'biji' => $soak->biji_keluar,
            'berat' => $soak->berat_keluar,
            'status' => 0
        ]);
    }

    /**
     * Handle the Soak "updated" event.
     */
    public function updated(Soak $soak): void
    {
        //
    }

    /**
     * Handle the Soak "deleted" event.
     */
    public function deleted(Soak $soak): void
    {
        //
    }

    /**
     * Handle the Soak "restored" event.
     */
    public function restored(Soak $soak): void
    {
        //
    }

    /**
     * Handle the Soak "force deleted" event.
     */
    public function forceDeleted(Soak $soak): void
    {
        //
    }
}
