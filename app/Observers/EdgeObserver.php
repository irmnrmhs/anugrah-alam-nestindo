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
            empty($edge->biji_keluar) &&
            empty($edge->berat_keluar)
        ) {
            $edge->biji_keluar = 0;
            $edge->berat_keluar = 0;
        }

        $history = History::where('identifiers_id', $edge->history->identifiers_id)
            ->where('asal', 'PR02SK')
            ->where('tujuan', 'PR03PC')
            ->first();

        if (!$history) {
            $history = History::create([
                'identifiers_id' => $edge->history->identifiers_id,
                'asal' => 'PR02SK',
                'tujuan' => 'PR03PC',
                'biji' => 0,
                'berat' => 0,
                'status' => 0
            ]);
        }

        $history->increment('biji', $edge->biji_keluar);
        $history->increment('berat', $edge->berat_keluar);
    }

        // History::create([
        //     'identifiers_id' => $edge->history->identifiers_id,
        //     'asal' => 'PR02SK',
        //     'tujuan' => 'PR03PC',
        //     'biji' => $edge->biji_keluar ?? 0,
        //     'berat' => $edge->berat_keluar ?? 0,
        //     'status' => 0
        // ]);

    /**
     * Handle the Edge "updated" event.
     */
    public function updated(Edge $edge): void
    {
        if (!$edge->wasChanged(['biji_keluar', 'berat_keluar'])) {
            return;
        }

        $history = History::where('identifiers_id', $edge->history->identifiers_id)
            ->where('asal', 'PR02SK')
            ->where('tujuan', 'PR03PC')
            ->first();

        if (!$history) return;

        $history->decrement('biji', $edge->getOriginal('biji_keluar') ?? 0);
        $history->decrement('berat', $edge->getOriginal('berat_keluar') ?? 0);

        $history->increment('biji', $edge->biji_keluar ?? 0);
        $history->increment('berat', $edge->berat_keluar ?? 0);
    }

        // $history = History::where('identifiers_id', $edge->history->identifiers_id)
        //     ->where('asal', 'PR02SK')
        //     ->where('tujuan', 'PR03PC')
        //     ->first();

        // if ($history) {
        //     $history->update([
        //         'biji'  => $edge->biji_masuk,
        //         'berat' => $edge->berat_masuk,
        //     ]);
        // }

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
            empty($edge->biji_keluar) &&
            empty($edge->berat_keluar)
        ) {
            return;
        }

        $history = History::where('identifiers_id', $edge->history->identifiers_id)
            ->where('asal', 'PR02SK')
            ->where('tujuan', 'PR03PC')
            ->first();

        if (!$history) return;

        if (
            ($edge->biji_keluar ?? 0) > $history->sisa_biji_cuci ||
            ($edge->berat_keluar ?? 0) > $history->sisa_berat_cuci
        ) {
            throw ValidationException::withMessages([
                'delete' => 'Data tidak dapat dihapus karena stok sudah digunakan'
            ]);
        }

        $history->decrement('biji', $edge->biji_keluar ?? 0);
        $history->decrement('berat', $edge->berat_keluar ?? 0);

        if ($history->biji <= 0 && $history->berat <= 0) {
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
