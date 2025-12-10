<?php

namespace App\Observers;

use App\Models\Dry;
use App\Models\History;

class DryObserver
{
    /**
     * Handle the Dry "created" event.
     */
    public function created(Dry $dry): void
    {
        History::create([
            'identifiers_id' => $dry->history->identifiers_id,
            'asal' => 'PR10PK',
            'tujuan' => 'PR11GP',
            'biji' => $dry->biji_keluar,
            'berat' => $dry->berat_keluar,
            'status' => 0
        ]);
    }

    /**
     * Handle the Dry "updated" event.
     */
    public function updated(Dry $dry): void
    {
        //
    }

    /**
     * Handle the Dry "deleted" event.
     */
    public function deleted(Dry $dry): void
    {
        //
    }

    /**
     * Handle the Dry "restored" event.
     */
    public function restored(Dry $dry): void
    {
        //
    }

    /**
     * Handle the Dry "force deleted" event.
     */
    public function forceDeleted(Dry $dry): void
    {
        //
    }
}
