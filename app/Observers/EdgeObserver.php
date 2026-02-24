<?php

namespace App\Observers;

use App\Models\Edge;
use App\Models\History;
use Illuminate\Validation\ValidationException;

class EdgeObserver
{
    /**
     * Handle the Edge "created" event.
     */
    public function created(Edge $edge): void
    {
        if (
            empty($edge->biji)
        ) {
            $edge->biji = 0;
        }

        $history = History::where('gcolors_id', $edge->history->gcolors_id)
            ->where('asal', 'PR02SK')
            ->where('tujuan', 'PR03PC')
            ->first();

        if (!$history) {
            $history = History::create([
                'gcolors_id' => $edge->history->gcolors_id,
                'asal' => 'PR02SK',
                'tujuan' => 'PR03PC',
                'biji' => 0,
                'berat' => 0,
            ]);
        }

        $history->increment('biji', $edge->biji);
    }

    /**
     * Handle the Edge "updated" event.
    */
    public function updated(Edge $edge): void
    {
        if (!$edge->wasChanged('biji')) {
            return;
        }

        $history = History::where('gcolors_id', $edge->history->gcolors_id)
            ->where('asal', 'PR02SK')
            ->where('tujuan', 'PR03PC')
            ->first();

        if (!$history) return;

        $history->decrement('biji', $edge->getOriginal('biji') ?? 0);

        $history->increment('biji', $edge->biji ?? 0);
    }

    public function updating(Edge $edge)
    {
        if (!$edge->isDirty('biji')) {
            return;
        }

        $history = History::where('gcolors_id', $edge->history->gcolors_id)
            ->where('asal', 'PR02SK')
            ->where('tujuan', 'PR03PC')
            ->first();

        if (!$history) return;

        $dipakaiBiji = $history->washes()->sum('biji_in');

        if ($edge->biji < $dipakaiBiji) {
            throw ValidationException::withMessages([
                'biji' => 'Biji keluar lebih kecil dari stok yang sudah dipakai proses berikutnya'
            ]);
        }
    }

    /**
     * Handle the Edge "deleted" event.
     */
    public function deleted(Edge $edge): void
    {
        // 
    }

    public function deleting(Edge $edge): void
    {
        if (
            empty($edge->biji)
        ) {
            return;
        }

        $history = History::where('gcolors_id', $edge->history->gcolors_id)
            ->where('asal', 'PR02SK')
            ->where('tujuan', 'PR03PC')
            ->first();

        if (!$history) return;

        if (
            ($edge->biji ?? 0) > $history->sisa_biji_cuci
        ) {
            throw ValidationException::withMessages([
                'delete' => 'Data tidak dapat dihapus karena stok sudah digunakan'
            ]);
        }

        $history->decrement('biji', $edge->biji ?? 0);

        if ($history->biji <= 0) {
            $history->delete();
        }
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
