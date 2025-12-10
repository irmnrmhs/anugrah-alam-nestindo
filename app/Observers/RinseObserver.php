<?php

namespace App\Observers;

use App\Models\Rinse;
use App\Models\History;

class RinseObserver
{
    /**
     * Handle the Rinse "created" event.
     */
    public function created(Rinse $rinse): void
    {
        History::create([
            'identifiers_id' => $rinse->history->identifiers_id,
            'asal' => 'PR07CB',
            'tujuan' => 'PR08MC',
            'biji' => $rinse->biji_keluar,
            'berat' => $rinse->berat_keluar,
            'status' => 0
        ]);
    }

    /**
     * Handle the Rinse "updated" event.
     */
    public function updated(Rinse $rinse): void
    {
        //
    }

    /**
     * Handle the Rinse "deleted" event.
     */
    public function deleted(Rinse $rinse): void
    {
        //
    }

    /**
     * Handle the Rinse "restored" event.
     */
    public function restored(Rinse $rinse): void
    {
        //
    }

    /**
     * Handle the Rinse "force deleted" event.
     */
    public function forceDeleted(Rinse $rinse): void
    {
        //
    }
}
