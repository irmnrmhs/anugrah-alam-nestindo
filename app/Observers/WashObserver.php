<?php

namespace App\Observers;

use App\Models\Wash;
use App\Models\History;
use Illuminate\Validation\ValidationException;

class WashObserver
{
    /**
     * Handle the Wash "created" event.
     */
    public function created(Wash $wash): void
    {
        if (
            empty($wash->biji_out)
        ) {
            $wash->biji_out = 0;
        }

        $history = History::where('gcolors_id', $wash->history->gcolors_id)
            ->where('asal', 'PR03PC')
            ->where('tujuan', 'PR04IK')
            ->first();

        if (!$history) {
            $history = History::create([
                'gcolors_id' => $wash->history->gcolors_id,
                'asal' => 'PR03PC',
                'tujuan' => 'PR04IK',
                'biji' => 0,
                'berat' => 0,
            ]);
        }

        $history->increment('biji', $wash->biji_out);
    }

    /**
     * Handle the Wash "updated" event.
     */
    public function updated(Wash $wash): void
    {
        if (!$wash->wasChanged(['biji_out'])) {
            return;
        }

        $history = History::where('gcolors_id', $wash->history->gcolors_id)
            ->where('asal', 'PR03PC')
            ->where('tujuan', 'PR04IK')
            ->first();

        if (!$history) return;

        $history->decrement('biji', $wash->getOriginal('biji_out') ?? 0);

        $history->increment('biji', $wash->biji_out ?? 0);
    }

    public function updating(Wash $wash)
    {
        if (!$wash->isDirty(['biji_out'])) {
            return;
        }

        $history = History::where('gcolors_id', $wash->history->gcolors_id)
            ->where('asal', 'PR03PC')
            ->where('tujuan', 'PR04IK')
            ->first();

        if (!$history) return;

        $dipakaiBiji = $history->corrections()->sum('biji');

        if ($wash->biji_out < $dipakaiBiji) {
            throw ValidationException::withMessages([
                'biji_keluar' => 'Biji keluar lebih kecil dari stok yang sudah dipakai proses berikutnya'
            ]);
        }
    }

    /**
     * Handle the Wash "deleted" event.
     */
    public function deleted(Wash $wash): void
    {
        // 
    }

    public function deleting(Wash $wash): void
    {
        if (
            empty($wash->biji_out)
        ) {
            return;
        }

        $history = History::where('gcolors_id', $wash->history->gcolors_id)
            ->where('asal', 'PR03PC')
            ->where('tujuan', 'PR04IK')
            ->first();

        if (!$history) return;

        if (
            ($wash->biji_out ?? 0) > $history->sisa_biji_koreksi
        ) {
            throw ValidationException::withMessages([
                'delete' => 'Data tidak dapat dihapus karena stok sudah digunakan'
            ]);
        }

        $history->decrement('biji', $wash->biji_out ?? 0);

        if ($history->biji <= 0 && $history->berat <= 0) {
            $history->delete();
        }
    }

    /**
     * Handle the Wash "restored" event.
     */
    public function restored(Wash $wash): void
    {
        //
    }

    /**
     * Handle the Wash "force deleted" event.
     */
    public function forceDeleted(Wash $wash): void
    {
        //
    }
}
