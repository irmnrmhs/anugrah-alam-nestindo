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
        if (!$rinse->isDirty(['biji_keluar', 'berat_keluar'])) {
            return;
        }

        $history = History::where('identifiers_id', $rinse->history->identifiers_id)
            ->where('asal', 'PR07CB')
            ->where('tujuan', 'PR08MC')
            ->first();

        if (!$history) return;

        $history->decrement('biji', $rinse->getOriginal('biji_keluar') ?? 0);
        $history->decrement('berat', $rinse->getOriginal('berat_keluar') ?? 0);

        $history->increment('biji', $rinse->biji_keluar ?? 0);
        $history->increment('berat', $rinse->berat_keluar ?? 0);
    }

    /**
     * Handle the Rinse "updated" event.
     */
    public function updated(Rinse $rinse): void
    {
        $history = History::where('identifiers_id', $rinse->history->identifiers_id)
            ->where('asal', 'PR07CB')
            ->where('tujuan', 'PR08MC')
            ->first();

        if (!$history) return;

        $history->decrement('biji', $rinse->biji_keluar ?? 0);
        $history->decrement('berat', $rinse->berat_keluar ?? 0);

        if ($history->biji <= 0 && $history->berat <= 0) {
            $history->delete();
        }
    }

    /**
     * Handle the Rinse "deleted" event.
     */
    public function deleted(Rinse $rinse): void
    {
        History::where('identifiers_id', $rinse->history->identifiers_id)
            ->where('asal', 'PR07CB')
            ->where('tujuan', 'PR08MC')
            ->delete();
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
