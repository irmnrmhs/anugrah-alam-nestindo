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
        if (!$correction->isDirty(['biji_keluar', 'berat_keluar'])) {
            return;
        }

        $history = History::where('identifiers_id', $correction->history->identifiers_id)
            ->where('asal', 'PR04IK')
            ->where('tujuan', 'PR05PB')
            ->first();

        if (!$history) return;

        $history->decrement('biji', $correction->getOriginal('biji_keluar') ?? 0);
        $history->decrement('berat', $correction->getOriginal('berat_keluar') ?? 0);

        $history->increment('biji', $correction->biji_keluar ?? 0);
        $history->increment('berat', $correction->berat_keluar ?? 0);
    }

    /**
     * Handle the Correction "updated" event.
     */
    public function updated(Correction $correction): void
    {
        $history = History::where('identifiers_id', $correction->history->identifiers_id)
            ->where('asal', 'PR04IK')
            ->where('tujuan', 'PR05PB')
            ->first();

        if (!$history) return;

        $history->decrement('biji', $correction->biji_keluar ?? 0);
        $history->decrement('berat', $correction->berat_keluar ?? 0);

        if ($history->biji <= 0 && $history->berat <= 0) {
            $history->delete();
        }
    }

    /**
     * Handle the Correction "deleted" event.
     */
    public function deleted(Correction $correction): void
    {
        History::where('identifiers_id', $correction->history->identifiers_id)
            ->where('asal', 'PR04IK')
            ->where('tujuan', 'PR05PB')
            ->delete();
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
