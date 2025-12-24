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
        if (!$pull->isDirty(['biji_keluar', 'berat_keluar'])) {
            return;
        }

        $history = History::where('identifiers_id', $pull->history->identifiers_id)
            ->where('asal', 'PR09KC')
            ->where('tujuan', 'PR10PK')
            ->first();

        if (!$history) return;

        $history->decrement('biji', $pull->getOriginal('biji_keluar') ?? 0);
        $history->decrement('berat', $pull->getOriginal('berat_keluar') ?? 0);

        $history->increment('biji', $pull->biji_keluar ?? 0);
        $history->increment('berat', $pull->berat_keluar ?? 0);
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

        if (!$history) return;

        $history->decrement('biji', $pull->biji_keluar ?? 0);
        $history->decrement('berat', $pull->berat_keluar ?? 0);

        if ($history->biji <= 0 && $history->berat <= 0) {
            $history->delete();
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
