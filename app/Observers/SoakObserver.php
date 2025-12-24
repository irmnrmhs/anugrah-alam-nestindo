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
        if (!$soak->isDirty(['biji_keluar', 'berat_keluar'])) {
            return;
        }

        $history = History::where('identifiers_id', $soak->history->identifiers_id)
            ->where('asal', 'PR06PR')
            ->where('tujuan', 'PR07CB')
            ->first();

        if (!$history) return;

        $history->decrement('biji', $soak->getOriginal('biji_keluar') ?? 0);
        $history->decrement('berat', $soak->getOriginal('berat_keluar') ?? 0);

        $history->increment('biji', $soak->biji_keluar ?? 0);
        $history->increment('berat', $soak->berat_keluar ?? 0);
    }

    /**
     * Handle the Soak "updated" event.
     */
    public function updated(Soak $soak): void
    {
        $history = History::where('identifiers_id', $soak->history->identifiers_id)
            ->where('asal', 'PR06PR')
            ->where('tujuan', 'PR07CB')
            ->first();

        if (!$history) return;

        $history->decrement('biji', $soak->biji_keluar ?? 0);
        $history->decrement('berat', $soak->berat_keluar ?? 0);

        if ($history->biji <= 0 && $history->berat <= 0) {
            $history->delete();
        }
    }

    /**
     * Handle the Soak "deleted" event.
     */
    public function deleted(Soak $soak): void
    {
        History::where('identifiers_id', $soak->history->identifiers_id)
            ->where('asal', 'PR06PR')
            ->where('tujuan', 'PR07CB')
            ->delete();
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
