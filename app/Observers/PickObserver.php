<?php

namespace App\Observers;

use App\Models\Pick;
use App\Models\History;

class PickObserver
{
    /**
     * Handle the Pick "created" event.
     */
    public function created(Pick $pick): void
    {
        History::create([
            'identifiers_id' => $pick->history->identifiers_id,
            'asal' => 'PR05PB',
            'tujuan' => 'PR06PR',
            'biji' => $pick->biji_keluar,
            'berat' => $pick->berat_keluar,
            'status' => 0
        ]);
    }

    /**
     * Handle the Pick "updated" event.
     */
    public function updated(Pick $pick): void
    {
        //
    }

    /**
     * Handle the Pick "deleted" event.
     */
    public function deleted(Pick $pick): void
    {
        //
    }

    /**
     * Handle the Pick "restored" event.
     */
    public function restored(Pick $pick): void
    {
        //
    }

    /**
     * Handle the Pick "force deleted" event.
     */
    public function forceDeleted(Pick $pick): void
    {
        //
    }
}
