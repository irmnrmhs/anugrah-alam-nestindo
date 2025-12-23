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
            'biji' => $dry->biji_keluar ?? 0,
            'berat' => $dry->berat_keluar ?? 0,
            'status' => 0
        ]);
    }

    /**
     * Handle the Dry "updated" event.
     */
    public function updated(Dry $dry): void
    {
        $history = History::where('identifiers_id', $dry->history->identifiers_id)
            ->where('asal', 'PR10PK')
            ->where('tujuan', 'PR11GP')
            ->first();

        if ($history) {
            $history->update([
                'biji'  => $dry->biji_masuk,
                'berat' => $dry->berat_masuk,
            ]);
        }
    }

    /**
     * Handle the Dry "deleted" event.
     */
    public function deleted(Dry $dry): void
    {
        History::where('identifiers_id', $dry->history->identifiers_id)
            ->where('asal', 'PR10PK')
            ->where('tujuan', 'PR11GP')
            ->delete();
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
