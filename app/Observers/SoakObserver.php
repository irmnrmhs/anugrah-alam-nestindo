<?php

namespace App\Observers;

use App\Models\Soak;
use App\Models\History;
use Illuminate\Validation\ValidationException;

class SoakObserver
{
    /**
     * Handle the Soak "created" event.
     */
    public function created(Soak $soak): void
    {
        if (
            empty($soak->biji)
        ) {
            $soak->biji = 0;
        }

        $history = History::where('gcolors_id', $soak->history->gcolors_id)
            ->where('asal', 'PR06PR')
            ->where('tujuan', 'PR07CB')
            ->first();

        if (!$history) {
            $history = History::create([
                'gcolors_id' => $soak->history->gcolors_id,
                'asal' => 'PR06PR',
                'tujuan' => 'PR07CB',
                'biji' => 0,
                'berat' => 0,
            ]);
        }

        $history->increment('biji', $soak->biji);
    }

    /**
     * Handle the Soak "updated" event.
     */
    public function updated(Soak $soak): void
    {
        if (!$soak->wasChanged(['biji'])) {
            return;
        }

        $history = History::where('gcolors_id', $soak->history->gcolors_id)
            ->where('asal', 'PR06PR')
            ->where('tujuan', 'PR07CB')
            ->first();

        if (!$history) return;

        $history->decrement('biji', $soak->getOriginal('biji') ?? 0);

        $history->increment('biji', $soak->biji ?? 0);
    }

    public function updating(Soak $soak)
    {
        if (!$soak->isDirty(['biji'])) {
            return;
        }

        $history = History::where('gcolors_id', $soak->history->gcolors_id)
            ->where('asal', 'PR06PR')
            ->where('tujuan', 'PR07CB')
            ->first();

        if (!$history) return;

        $dipakaiBiji = $history->rinses()->sum('biji');

        if ($soak->biji < $dipakaiBiji) {
            throw ValidationException::withMessages([
                'biji' => 'Biji keluar lebih kecil dari stok yang sudah dipakai proses berikutnya'
            ]);
        }
    }

    /**
     * Handle the Soak "deleted" event.
     */
    public function deleted(Soak $soak): void
    {
        // 
    }

    public function deleting(Soak $soak): void
    {
        if (
            empty($soak->biji)
        ) {
            return;
        }

        $history = History::where('gcolors_id', $soak->history->gcolors_id)
            ->where('asal', 'PR06PR')
            ->where('tujuan', 'PR07CB')
            ->first();

        if (!$history) return;

        if (
            ($soak->biji ?? 0) > $history->sisa_biji_bilas
        ) {
            throw ValidationException::withMessages([
                'delete' => 'Data tidak dapat dihapus karena stok sudah digunakan'
            ]);
        }

        $history->decrement('biji', $soak->biji ?? 0);

        if ($history->biji <= 0 && $history->berat <= 0) {
            $history->delete();
        }
    }

    /**
     * Handle the Soak "restored" event.
     */
    public function restored(Soak $soak): void
    {
        //
    }

    /**
     * Handle the Soak "force deleted" event.
     */
    public function forceDeleted(Soak $soak): void
    {
        //
    }
}
