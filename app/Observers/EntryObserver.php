<?php

namespace App\Observers;

use App\Models\Entry;
use App\Models\History;

class EntryObserver
{
    /**
     * Handle the Entry "created" event.
     */
    public function created(Entry $entry): void
    {
        if (!$entry->isDirty(['biji_keluar', 'berat_keluar'])) {
            return;
        }

        $history = History::where('identifiers_id', $entry->history->identifiers_id)
            ->where('asal', 'PR08MC')
            ->where('tujuan', 'PR09KC')
            ->first();

        if (!$history) return;

        $history->decrement('biji', $entry->getOriginal('biji_keluar') ?? 0);
        $history->decrement('berat', $entry->getOriginal('berat_keluar') ?? 0);

        $history->increment('biji', $entry->biji_keluar ?? 0);
        $history->increment('berat', $entry->berat_keluar ?? 0);
    }

    /**
     * Handle the Entry "updated" event.
     */
    public function updated(Entry $entry): void
    {
        $history = History::where('identifiers_id', $entry->history->identifiers_id)
            ->where('asal', 'PR08MC')
            ->where('tujuan', 'PR09KC')
            ->first();

        if (!$history) return;

        $history->decrement('biji', $entry->biji_keluar ?? 0);
        $history->decrement('berat', $entry->berat_keluar ?? 0);

        if ($history->biji <= 0 && $history->berat <= 0) {
            $history->delete();
        }
    }

    /**
     * Handle the Entry "deleted" event.
     */
    public function deleted(Entry $entry): void
    {
        History::where('identifiers_id', $entry->history->identifiers_id)
            ->where('asal', 'PR08MC')
            ->where('tujuan', 'PR09KC')
            ->delete();
    }

    /**
     * Handle the Entry "restored" event.
     */
    public function restored(Entry $entry): void
    {
        //
    }

    /**
     * Handle the Entry "force deleted" event.
     */
    public function forceDeleted(Entry $entry): void
    {
        //
    }
}
