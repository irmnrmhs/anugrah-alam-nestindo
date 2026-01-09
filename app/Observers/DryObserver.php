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
        if (
            empty($dry->biji_keluar) &&
            empty($dry->berat_keluar)
        ) {
            return;
        }

        $history = History::where('identifiers_id', $dry->history->identifiers_id)
            ->where('asal', 'PR10PK')
            ->where('tujuan', 'PR11GP')
            ->first();

        if (!$history) {
            $history = History::create([
                'identifiers_id' => $dry->history->identifiers_id,
                'asal' => 'PR10PK',
                'tujuan' => 'PR11GP',
                'biji' => 0,
                'berat' => 0,
                'status' => 0
            ]);
        }

        $history->increment('biji', $dry->biji_keluar);
        $history->increment('berat', $dry->berat_keluar);   
    }

    /**
     * Handle the Dry "updated" event.
     */
    public function updated(Dry $dry): void
    {
        if (!$dry->wasChanged(['biji_keluar', 'berat_keluar'])) {
            return;
        }

        $history = History::where('identifiers_id', $dry->history->identifiers_id)
            ->where('asal', 'PR10PK')
            ->where('tujuan', 'PR11GP')
            ->first();

        if (!$history) return;

        $history->decrement('biji', $dry->getOriginal('biji_keluar') ?? 0);
        $history->decrement('berat', $dry->getOriginal('berat_keluar') ?? 0);

        $history->increment('biji', $dry->biji_keluar ?? 0);
        $history->increment('berat', $dry->berat_keluar ?? 0);
    }

    /**
     * Handle the Dry "deleted" event.
     */
    public function deleted(Dry $dry): void
    {
        if (
            empty($dry->biji_keluar) &&
            empty($dry->berat_keluar)
        ) {
            return;
        }

        $history = History::where('identifiers_id', $dry->history->identifiers_id)
            ->where('asal', 'PR10PK')
            ->where('tujuan', 'PR11GP')
            ->first();

        if (!$history) return;

        $history->decrement('biji', $dry->biji_keluar ?? 0);
        $history->decrement('berat', $dry->berat_keluar ?? 0);

        if ($history->biji <= 0 && $history->berat <= 0) {
            $history->delete();
        }
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
