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
            empty($wash->biji_keluar) &&
            empty($wash->berat_keluar)
        ) {
            $wash->biji_keluar = 0;
            $wash->berat_keluar = 0;
        }

        $history = History::where('identifiers_id', $wash->history->identifiers_id)
            ->where('asal', 'PR03PC')
            ->where('tujuan', 'PR04IK')
            ->first();

        if (!$history) {
            $history = History::create([
                'identifiers_id' => $wash->history->identifiers_id,
                'asal' => 'PR03PC',
                'tujuan' => 'PR04IK',
                'biji' => 0,
                'berat' => 0,
                'status' => 0
            ]);
        }

        $history->increment('biji', $wash->biji_keluar);
        $history->increment('berat', $wash->berat_keluar);
    }

    /**
     * Handle the Wash "updated" event.
     */
    public function updated(Wash $wash): void
    {
        if (!$wash->wasChanged(['biji_keluar', 'berat_keluar'])) {
            return;
        }

        $history = History::where('identifiers_id', $wash->history->identifiers_id)
            ->where('asal', 'PR03PC')
            ->where('tujuan', 'PR04IK')
            ->first();

        if (!$history) return;

        $history->decrement('biji', $wash->getOriginal('biji_keluar') ?? 0);
        $history->decrement('berat', $wash->getOriginal('berat_keluar') ?? 0);

        $history->increment('biji', $wash->biji_keluar ?? 0);
        $history->increment('berat', $wash->berat_keluar ?? 0);
    }

    public function updating(Wash $wash)
    {
        if (!$wash->isDirty(['biji_keluar', 'berat_keluar'])) {
            return;
        }

        $history = History::where('identifiers_id', $wash->history->identifiers_id)
            ->where('asal', 'PR03PC')
            ->where('tujuan', 'PR04IK')
            ->first();

        if (!$history) return;

        $dipakaiBiji = $history->corrections()->sum('biji_masuk');
        $dipakaiBerat = $history->corrections()->sum('berat_masuk');

        if ($wash->biji_keluar < $dipakaiBiji) {
            throw ValidationException::withMessages([
                'biji_keluar' => 'Biji keluar lebih kecil dari stok yang sudah dipakai proses berikutnya'
            ]);
        }

        if ($wash->berat_keluar < $dipakaiBerat) {
            throw ValidationException::withMessages([
                'berat_keluar' => 'Berat keluar lebih kecil dari stok yang sudah dipakai proses berikutnya'
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
            empty($wash->biji_keluar) &&
            empty($wash->berat_keluar)
        ) {
            return;
        }

        $history = History::where('identifiers_id', $wash->history->identifiers_id)
            ->where('asal', 'PR03PC')
            ->where('tujuan', 'PR04IK')
            ->first();

        if (!$history) return;

        if (
            ($wash->biji_keluar ?? 0) > $history->sisa_biji_koreksi ||
            ($wash->berat_keluar ?? 0) > $history->sisa_berat_koreksi
        ) {
            throw ValidationException::withMessages([
                'delete' => 'Data tidak dapat dihapus karena stok sudah digunakan'
            ]);
        }

        $history->decrement('biji', $wash->biji_keluar ?? 0);
        $history->decrement('berat', $wash->berat_keluar ?? 0);

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
