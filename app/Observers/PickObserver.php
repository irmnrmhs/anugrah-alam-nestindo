<?php

namespace App\Observers;

use App\Models\Pick;
use App\Models\History;
use Illuminate\Validation\ValidationException;

class PickObserver
{
    /**
     * Handle the Pick "created" event.
     */
    public function created(Pick $pick): void
    {
        if (
            empty($pick->biji)
        ) {
            $pick->biji = 0;
        }

        $history = History::where('gcolors_id', $pick->history->gcolors_id)
            ->where('asal', 'PR05PB')
            ->where('tujuan', 'PR06PR')
            ->first();

        if (!$history) {
            $history = History::create([
                'gcolors_id' => $pick->history->gcolors_id,
                'asal' => 'PR05PB',
                'tujuan' => 'PR06PR',
                'biji' => 0,
                'berat' => 0,
            ]);
        }

        $history->increment('biji', $pick->biji);
    }

    /**
     * Handle the Pick "updated" event.
     */
    public function updated(Pick $pick): void
    {
        if (!$pick->wasChanged(['biji'])) {
            return;
        }

        $history = History::where('gcolors_id', $pick->history->gcolors_id)
            ->where('asal', 'PR05PB')
            ->where('tujuan', 'PR06PR')
            ->first();

        if (!$history) return;

        $history->decrement('biji', $pick->getOriginal('biji') ?? 0);

        $history->increment('biji', $pick->biji ?? 0);
    }

    public function updating(Pick $pick)
    {
        if (!$pick->isDirty(['biji'])) {
            return;
        }

        $history = History::where('gcolors_id', $pick->history->gcolors_id)
            ->where('asal', 'PR05PB')
            ->where('tujuan', 'PR06PR')
            ->first();

        if (!$history) return;

        $dipakaiBiji = $history->soaks()->sum('biji');

        if ($pick->biji < $dipakaiBiji) {
            throw ValidationException::withMessages([
                'biji' => 'Biji keluar lebih kecil dari stok yang sudah dipakai proses berikutnya'
            ]);
        }
    }

    /**
     * Handle the Pick "deleted" event.
     */
    public function deleted(Pick $pick): void
    {
        // 
    }

    public function deleting(Pick $pick): void
    {
        if (
            empty($pick->biji)
        ) {
            return;
        }

        $history = History::where('gcolors_id', $pick->history->gcolors_id)
            ->where('asal', 'PR05PB')
            ->where('tujuan', 'PR06PR')
            ->first();

        if (!$history) return;

        if (
            ($pick->biji ?? 0) > $history->sisa_biji_rendam
        ) {
            throw ValidationException::withMessages([
                'delete' => 'Data tidak dapat dihapus karena stok sudah digunakan'
            ]);
        }

        $history->decrement('biji', $pick->biji ?? 0);

        if ($history->biji <= 0 && $history->berat <= 0) {
            $history->delete();
        }
    }

    /**
     * Handle the Pick "restored" event.
     */
    public function restored(Pick $pick): void
    {
        //
    }

    /**
     * Handle the Pick "force deleted" event.
     */
    public function forceDeleted(Pick $pick): void
    {
        //
    }
}
