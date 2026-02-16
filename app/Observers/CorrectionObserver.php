<?php

namespace App\Observers;

use App\Models\History;
use App\Models\Correction;
use Illuminate\Validation\ValidationException;

class CorrectionObserver
{
    /**
     * Handle the Correction "created" event.
     */
    public function created(Correction $correction): void
    {
        if (
            empty($correction->biji)
        ) {
            $correction->biji = 0;
        }

        $history = History::where('gcolors_id', $correction->history->gcolors_id)
            ->where('asal', 'PR04IK')
            ->where('tujuan', 'PR05PB')
            ->first();

        if (!$history) {
            $history = History::create([
                'gcolors_id' => $correction->history->gcolors_id,
                'asal' => 'PR04IK',
                'tujuan' => 'PR05PB',
                'biji' => 0,
                'berat' => 0,
            ]);
        }

        $history->increment('biji', $correction->biji);
    }

    /**
     * Handle the Correction "updated" event.
     */
    public function updated(Correction $correction): void
    {
        if (!$correction->wasChanged(['biji'])) {
            return;
        }

        $history = History::where('gcolors_id', $correction->history->gcolors_id)
            ->where('asal', 'PR04IK')
            ->where('tujuan', 'PR05PB')
            ->first();

        if (!$history) return;

        $history->decrement('biji', $correction->getOriginal('biji') ?? 0);

        $history->increment('biji', $correction->biji ?? 0);
    }

    public function updating(Correction $correction)
    {
        if (!$correction->isDirty(['biji_keluar'])) {
            return;
        }

        $history = History::where('gcolors_id', $correction->history->gcolors_id)
            ->where('asal', 'PR04IK')
            ->where('tujuan', 'PR05PB')
            ->first();

        if (!$history) return;

        $dipakaiBiji = $history->picks()->sum('biji');

        if ($correction->biji < $dipakaiBiji) {
            throw ValidationException::withMessages([
                'biji' => 'Biji keluar lebih kecil dari stok yang sudah dipakai proses berikutnya'
            ]);
        }
    }

    /**
     * Handle the Correction "deleted" event.
     */
    public function deleted(Correction $correction): void
    {
        // 
    }

    public function deleting(Correction $correction): void
    {
        if (
            empty($correction->biji)
        ) {
            return;
        }

        $history = History::where('gcolors_id', $correction->history->gcolors_id)
            ->where('asal', 'PR04IK')
            ->where('tujuan', 'PR05PB')
            ->first();

        if (!$history) return;

        if (
            ($correction->biji ?? 0) > $history->sisa_biji_cabut
        ) {
            throw ValidationException::withMessages([
                'delete' => 'Data tidak dapat dihapus karena stok sudah digunakan'
            ]);
        }

        $history->decrement('biji', $correction->biji ?? 0);

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
