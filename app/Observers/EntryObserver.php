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
        History::create([
            'identifiers_id' => $entry->history->identifiers_id,
            'asal' => 'PR08MC',
            'tujuan' => 'PR09KC',
            'biji' => $entry->biji_keluar,
            'berat' => $entry->berat_keluar,
            'status' => 0
        ]);
    }

    /**
     * Handle the Entry "updated" event.
     */
    public function updated(Entry $entry): void
    {
        //
    }

    /**
     * Handle the Entry "deleted" event.
     */
    public function deleted(Entry $entry): void
    {
        //
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
