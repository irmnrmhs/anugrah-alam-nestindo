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
        if (!$pick->isDirty(['biji_keluar', 'berat_keluar'])) {
            return;
        }

        $history = History::where('identifiers_id', $pick->history->identifiers_id)
            ->where('asal', 'PR05PB')
            ->where('tujuan', 'PR06PR')
            ->first();

        if (!$history) return;

        $history->decrement('biji', $pick->getOriginal('biji_keluar') ?? 0);
        $history->decrement('berat', $pick->getOriginal('berat_keluar') ?? 0);

        $history->increment('biji', $pick->biji_keluar ?? 0);
        $history->increment('berat', $pick->berat_keluar ?? 0);
    }

    /**
     * Handle the Pick "updated" event.
     */
    public function updated(Pick $pick): void
    {
        $history = History::where('identifiers_id', $pick->history->identifiers_id)
            ->where('asal', 'PR05PB')
            ->where('tujuan', 'PR06PR')
            ->first();

        if (!$history) return;

        $history->decrement('biji', $pick->biji_keluar ?? 0);
        $history->decrement('berat', $pick->berat_keluar ?? 0);

        if ($history->biji <= 0 && $history->berat <= 0) {
            $history->delete();
        }
    }

    /**
     * Handle the Pick "deleted" event.
     */
    public function deleted(Pick $pick): void
    {
        History::where('identifiers_id', $pick->history->identifiers_id)
            ->where('asal', 'PR05PB')
            ->where('tujuan', 'PR06PR')
            ->delete();
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
