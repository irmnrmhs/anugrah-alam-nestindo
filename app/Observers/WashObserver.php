<?php

namespace App\Observers;

use App\Models\Wash;
use App\Models\History;

class WashObserver
{
    /**
     * Handle the Wash "created" event.
     */
    public function created(Wash $wash): void
    {
        History::create([
            'identifiers_id' => $wash->history->identifiers_id,
            'asal' => 'PR03PC',
            'tujuan' => 'PR04IK',
            'biji' => $wash->biji_masuk,
            'berat' => $wash->berat_masuk,
            'status' => 0
        ]);
    }

    /**
     * Handle the Wash "updated" event.
     */
    public function updated(Wash $wash): void
    {
        //
    }

    /**
     * Handle the Wash "deleted" event.
     */
    public function deleted(Wash $wash): void
    {
        //
    }

    /**
     * Handle the Wash "restored" event.
     */
    public function restored(Wash $wash): void
    {
        //
    }

    /**
     * Handle the Wash "force deleted" event.
     */
    public function forceDeleted(Wash $wash): void
    {
        //
    }
}
