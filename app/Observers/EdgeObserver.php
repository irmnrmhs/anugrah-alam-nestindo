<?php

namespace App\Observers;

use App\Models\Edge;
use App\Models\History;

class EdgeObserver
{
    /**
     * Handle the Edge "created" event.
     */
    public function created(Edge $edge): void
    {
        History::create([
            'identifiers_id' => $edge->history->identifiers_id,
            'asal' => 'PR02SK',
            'tujuan' => 'PR03PC',
            'biji' => $edge->biji_masuk,
            'berat' => $edge->berat_masuk,
            'status' => 0
        ]);
    }

    /**
     * Handle the Edge "updated" event.
     */
    public function updated(Edge $edge): void
    {
        //
    }

    /**
     * Handle the Edge "deleted" event.
     */
    public function deleted(Edge $edge): void
    {
        //
    }

    /**
     * Handle the Edge "restored" event.
     */
    public function restored(Edge $edge): void
    {
        //
    }

    /**
     * Handle the Edge "force deleted" event.
     */
    public function forceDeleted(Edge $edge): void
    {
        //
    }
}
