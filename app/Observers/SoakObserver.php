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
            empty($soak->biji_keluar) &&
            empty($soak->berat_keluar)
        ) {
            $soak->biji_keluar = 0;
            $soak->berat_keluar = 0;
        }

        $history = History::where('identifiers_id', $soak->history->identifiers_id)
            ->where('asal', 'PR06PR')
            ->where('tujuan', 'PR07CB')
            ->first();

        if (!$history) {
            $history = History::create([
                'identifiers_id' => $soak->history->identifiers_id,
                'asal' => 'PR06PR',
                'tujuan' => 'PR07CB',
                'biji' => 0,
                'berat' => 0,
                'status' => 0
            ]);
        }

        $history->increment('biji', $soak->biji_keluar);
        $history->increment('berat', $soak->berat_keluar);
    }

    /**
     * Handle the Soak "updated" event.
     */
    public function updated(Soak $soak): void
    {
        if (!$soak->wasChanged(['biji_keluar', 'berat_keluar'])) {
            return;
        }

        $history = History::where('identifiers_id', $soak->history->identifiers_id)
            ->where('asal', 'PR06PR')
            ->where('tujuan', 'PR07CB')
            ->first();

        if (!$history) return;

        $history->decrement('biji', $soak->getOriginal('biji_keluar') ?? 0);
        $history->decrement('berat', $soak->getOriginal('berat_keluar') ?? 0);

        $history->increment('biji', $soak->biji_keluar ?? 0);
        $history->increment('berat', $soak->berat_keluar ?? 0);
    }

    public function updating(Soak $soak)
    {
        if (!$soak->isDirty(['biji_keluar', 'berat_keluar'])) {
            return;
        }

        $history = History::where('identifiers_id', $soak->history->identifiers_id)
            ->where('asal', 'PR06PR')
            ->where('tujuan', 'PR07CB')
            ->first();

        if (!$history) return;

        $dipakaiBiji = $history->rinses()->sum('biji_masuk');
        $dipakaiBerat = $history->rinses()->sum('berat_masuk');

        if ($soak->biji_keluar < $dipakaiBiji) {
            throw ValidationException::withMessages([
                'biji_keluar' => 'Biji keluar lebih kecil dari stok yang sudah dipakai proses berikutnya'
            ]);
        }

        if ($soak->berat_keluar < $dipakaiBerat) {
            throw ValidationException::withMessages([
                'berat_keluar' => 'Berat keluar lebih kecil dari stok yang sudah dipakai proses berikutnya'
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
            empty($soak->biji_keluar) &&
            empty($soak->berat_keluar)
        ) {
            return;
        }

        $history = History::where('identifiers_id', $soak->history->identifiers_id)
            ->where('asal', 'PR06PR')
            ->where('tujuan', 'PR07CB')
            ->first();

        if (!$history) return;

        if (
            ($soak->biji_keluar ?? 0) > $history->sisa_biji_bilas ||
            ($soak->berat_keluar ?? 0) > $history->sisa_berat_bilas
        ) {
            throw ValidationException::withMessages([
                'delete' => 'Data tidak dapat dihapus karena stok sudah digunakan'
            ]);
        }

        $history->decrement('biji', $soak->biji_keluar ?? 0);
        $history->decrement('berat', $soak->berat_keluar ?? 0);

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
