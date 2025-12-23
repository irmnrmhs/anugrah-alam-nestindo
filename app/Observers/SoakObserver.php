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
        History::create([
            'identifiers_id' => $soak->history->identifiers_id,
            'asal' => 'PR06PR',
            'tujuan' => 'PR07CB',
            'biji' => $soak->biji_keluar ?? 0,
            'berat' => $soak->berat_keluar ?? 0,
            'status' => 0
        ]);
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

        if ($history) {
            $history->update([
                'biji'  => $soak->biji_masuk,
                'berat' => $soak->berat_masuk,
            ]);
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
