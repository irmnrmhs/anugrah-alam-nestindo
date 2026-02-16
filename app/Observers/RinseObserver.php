<?php

namespace App\Observers;

use App\Models\Rinse;
use App\Models\History;
use Illuminate\Validation\ValidationException;

class RinseObserver
{
    /**
     * Handle the Rinse "created" event.
     */
    public function created(Rinse $rinse): void
    {
        if (
            empty($rinse->biji)
        ) {
            $rinse->biji = 0;
        }

        $history = History::where('gcolors_id', $rinse->history->gcolors_id)
            ->where('asal', 'PR07CB')
            ->where('tujuan', 'PR08MC')
            ->first();

        if (!$history) {
            $history = History::create([
                'gcolors_id' => $rinse->history->gcolors_id,
                'asal' => 'PR07CB',
                'tujuan' => 'PR08MC',
                'biji' => 0,
                'berat' => 0,
                'status' => 0
            ]);
        }

        $history->increment('biji', $rinse->biji);
    }

    /**
     * Handle the Rinse "updated" event.
     */
    public function updated(Rinse $rinse): void
    {
        if (!$rinse->wasChanged(['biji'])) {
            return;
        }

        $history = History::where('gcolors_id', $rinse->history->gcolors_id)
            ->where('asal', 'PR07CB')
            ->where('tujuan', 'PR08MC')
            ->first();

        if (!$history) return;

        $history->decrement('biji', $rinse->getOriginal('biji') ?? 0);

        $history->increment('biji', $rinse->biji ?? 0);
    }

    public function updating(Rinse $rinse)
    {
        if (!$rinse->isDirty(['biji'])) {
            return;
        }

        $history = History::where('gcolors_id', $rinse->history->gcolors_id)
            ->where('asal', 'PR07CB')
            ->where('tujuan', 'PR08MC')
            ->first();

        if (!$history) return;

        $dipakaiBiji = $history->entries()->sum('biji');

        if ($rinse->biji < $dipakaiBiji) {
            throw ValidationException::withMessages([
                'biji_keluar' => 'Biji keluar lebih kecil dari stok yang sudah dipakai proses berikutnya'
            ]);
        }
    }

    /**
     * Handle the Rinse "deleted" event.
     */
    public function deleted(Rinse $rinse): void
    {
        // 
    }

    public function deleting(Rinse $rinse): void
    {
        if (
            empty($rinse->biji)
        ) {
            return;
        }

        $history = History::where('gcolors_id', $rinse->history->gcolors_id)
            ->where('asal', 'PR07CB')
            ->where('tujuan', 'PR08MC')
            ->first();

        if (!$history) return;

        if (
            ($rinse->biji ?? 0) > $history->sisa_biji_entry
        ) {
            throw ValidationException::withMessages([
                'delete' => 'Data tidak dapat dihapus karena stok sudah digunakan'
            ]);
        }

        $history->decrement('biji', $rinse->biji ?? 0);

        if ($history->biji <= 0 && $history->berat <= 0) {
            $history->delete();
        }
    }

    /**
     * Handle the Rinse "restored" event.
     */
    public function restored(Rinse $rinse): void
    {
        //
    }

    /**
     * Handle the Rinse "force deleted" event.
     */
    public function forceDeleted(Rinse $rinse): void
    {
        //
    }
}
