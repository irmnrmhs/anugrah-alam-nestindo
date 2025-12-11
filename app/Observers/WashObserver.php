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
            'biji' => $wash->biji_keluar,
            'berat' => $wash->berat_keluar,
            'status' => 0
        ]);
    }

    /**
     * Handle the Wash "updated" event.
     */
    public function updated(Wash $wash): void
    {
        $history = History::where('identifiers_id', $wash->history->identifiers_id)
            ->where('asal', 'PR03PC')
            ->where('tujuan', 'PR04IK')
            ->first();

        if ($history) {
            $history->update([
                'biji'  => $wash->biji_masuk,
                'berat' => $wash->berat_masuk,
            ]);
        }
    }

    /**
     * Handle the Wash "deleted" event.
     */
    public function deleted(Wash $wash): void
    {
        History::where('identifiers_id', $wash->history->identifiers_id)
            ->where('asal', 'PR03PC')
            ->where('tujuan', 'PR04IK')
            ->delete();
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
