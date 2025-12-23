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
        History::create([
            'identifiers_id' => $correction->history->identifiers_id,
            'asal' => 'PR04IK',
            'tujuan' => 'PR05PB',
            'biji' => $correction->biji_keluar ?? 0,
            'berat' => $correction->berat_keluar ?? 0,
            'status' => 0
        ]);
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

        if ($history) {
            $history->update([
                'biji'  => $correction->biji_masuk,
                'berat' => $correction->berat_masuk,
            ]);
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
