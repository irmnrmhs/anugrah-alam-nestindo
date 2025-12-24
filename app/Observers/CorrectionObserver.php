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
        if (
            empty($correction->biji_keluar) &&
            empty($correction->berat_keluar)
        ) {
            return;
        }

        $history = History::where('identifiers_id', $correction->history->identifiers_id)
            ->where('asal', 'PR04IK')
            ->where('tujuan', 'PR05PB')
            ->first();

        if (!$history) {
            $history = History::create([
                'identifiers_id' => $correction->history->identifiers_id,
                'asal' => 'PR04IK',
                'tujuan' => 'PR05PB',
                'biji' => 0,
                'berat' => 0,
                'status' => 0
            ]);
        }

        $history->increment('biji', $correction->biji_keluar);
        $history->increment('berat', $correction->berat_keluar);
    }

    /**
     * Handle the Correction "updated" event.
     */
    public function updated(Correction $correction): void
    {
        if (!$correction->wasChanged(['biji_keluar', 'berat_keluar'])) {
            return;
        }

        $history = History::where('identifiers_id', $correction->history->identifiers_id)
            ->where('asal', 'PR04IK')
            ->where('tujuan', 'PR05PB')
            ->first();

        if (!$history) return;

        $history->decrement('biji', $correction->getOriginal('biji_keluar') ?? 0);
        $history->decrement('berat', $correction->getOriginal('berat_keluar') ?? 0);

        $history->increment('biji', $correction->biji_keluar ?? 0);
        $history->increment('berat', $correction->berat_keluar ?? 0);
    }

    /**
     * Handle the Correction "deleted" event.
     */
    public function deleted(Correction $correction): void
    {
        if (
            empty($correction->biji_keluar) &&
            empty($correction->berat_keluar)
        ) {
            return;
        }

        $history = History::where('identifiers_id', $correction->history->identifiers_id)
            ->where('asal', 'PR04IK')
            ->where('tujuan', 'PR05PB')
            ->first();

        if (!$history) return;

        $history->decrement('biji', $correction->biji_keluar ?? 0);
        $history->decrement('berat', $correction->berat_keluar ?? 0);

        if ($history->biji <= 0 && $history->berat <= 0) {
            $history->delete();
        }
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
