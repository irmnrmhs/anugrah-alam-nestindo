<?php

namespace App\Observers;

use App\Models\Dry;
use App\Models\History;
use Illuminate\Validation\ValidationException;

class DryObserver
{
    /**
     * Handle the Dry "created" event.
     */
    public function created(Dry $dry): void
    {
        if (
            empty($dry->biji)
        ) {
            return;
        }

        $history = History::where('gcolors_id', $dry->history->gcolors_id)
            ->where('asal', 'PR10PK')
            ->where('tujuan', 'PR11GP')
            ->first();

        if (!$history) {
            $history = History::create([
                'gcolors_id' => $dry->history->gcolors_id,
                'asal' => 'PR10PK',
                'tujuan' => 'PR11GP',
                'biji' => 0,
                'berat' => 0,
            ]);
        }

        $history->increment('biji', $dry->biji);  
    }

    /**
     * Handle the Dry "updated" event.
     */
    public function updated(Dry $dry): void
    {
        if (!$dry->wasChanged('biji')) {
            return;
        }

        $history = History::where('gcolors_id', $dry->history->gcolors_id)
            ->where('asal', 'PR10PK')
            ->where('tujuan', 'PR11GP')
            ->first();

        if (!$history) return;

        $history->decrement('biji', $dry->getOriginal('biji') ?? 0);

        $history->increment('biji', $dry->biji ?? 0);
    }

    public function updating(Dry $dry)
    {
        if (!$dry->isDirty('biji')) {
            return;
        }

        $history = History::where('gcolors_id', $dry->history->gcolors_id)
            ->where('asal', 'PR10PK')
            ->where('tujuan', 'PR11GP')
            ->first();

        if (!$history) return;

        $dipakaiBiji = $history->products()->sum('biji');

        if ($dry->biji_keluar < $dipakaiBiji) {
            throw ValidationException::withMessages([
                'biji' => 'Biji keluar lebih kecil dari stok yang sudah dipakai proses berikutnya'
            ]);
        }
    }

    /**
     * Handle the Dry "deleted" event.
     */
    public function deleted(Dry $dry): void
    {
        //
    }

    public function deleting(Dry $dry): void
    {
        if (empty($dry->biji)) {
            return;
        }

        $history = History::where('gcolors_id', $dry->history->gcolors_id)
            ->where('asal', 'PR10PK')
            ->where('tujuan', 'PR11GP')
            ->first();

        if (!$history) return;

        if (
            ($dry->biji ?? 0) > $history->sisa_biji_produk
        ) {
            throw ValidationException::withMessages([
                'delete' => 'Data tidak dapat dihapus karena stok sudah digunakan'
            ]);
        }

        $history->decrement('biji', $dry->biji ?? 0);

        if ($history->biji <= 0) {
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
