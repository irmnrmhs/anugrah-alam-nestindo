<?php

namespace App\Observers;

use App\Models\Pull;
use App\Models\History;

class PullObserver
{
    /**
     * Handle the Pull "created" event.
     */
    public function created(Pull $pull): void
    {
        History::create([
            'identifiers_id' => $pull->history->identifiers_id,
            'asal' => 'PR09KC',
            'tujuan' => 'PR10PK',
            'biji' => $pull->biji_keluar,
            'berat' => $pull->berat_keluar,
            'status' => 0
        ]);
    }

    /**
     * Handle the Pull "updated" event.
     */
    public function updated(Pull $pull): void
    {
        $history = History::where('identifiers_id', $pull->history->identifiers_id)
            ->where('asal', 'PR09KC')
            ->where('tujuan', 'PR10PK')
            ->first();

        if ($history) {
            $history->update([
                'biji'  => $pull->biji_masuk,
                'berat' => $pull->berat_masuk,
            ]);
        }
    }

    /**
     * Handle the Pull "deleted" event.
     */
    public function deleted(Pull $pull): void
    {
        History::where('identifiers_id', $pull->history->identifiers_id)
            ->where('asal', 'PR09KC')
            ->where('tujuan', 'PR10PK')
            ->delete();
    }

    /**
     * Handle the Pull "restored" event.
     */
    public function restored(Pull $pull): void
    {
        //
    }

    /**
     * Handle the Pull "force deleted" event.
     */
    public function forceDeleted(Pull $pull): void
    {
        //
    }
}
