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
        if (!$wash->isDirty(['biji_keluar', 'berat_keluar'])) {
            return;
        }

        $history = History::where('identifiers_id', $wash->history->identifiers_id)
            ->where('asal', 'PR03PC')
            ->where('tujuan', 'PR04IK')
            ->first();

        if (!$history) return;

        $history->decrement('biji', $wash->getOriginal('biji_keluar') ?? 0);
        $history->decrement('berat', $wash->getOriginal('berat_keluar') ?? 0);

        $history->increment('biji', $wash->biji_keluar ?? 0);
        $history->increment('berat', $wash->berat_keluar ?? 0);
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

        if (!$history) return;

        $history->decrement('biji', $wash->biji_keluar ?? 0);
        $history->decrement('berat', $wash->berat_keluar ?? 0);

        if ($history->biji <= 0 && $history->berat <= 0) {
            $history->delete();
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
