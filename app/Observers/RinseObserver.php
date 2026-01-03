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
            empty($rinse->biji_keluar) &&
            empty($rinse->berat_keluar)
        ) {
            return;
        }

        $history = History::where('identifiers_id', $rinse->history->identifiers_id)
            ->where('asal', 'PR07CB')
            ->where('tujuan', 'PR08MC')
            ->first();

        if (!$history) {
            $history = History::create([
                'identifiers_id' => $rinse->history->identifiers_id,
                'asal' => 'PR07CB',
                'tujuan' => 'PR08MC',
                'biji' => 0,
                'berat' => 0,
                'status' => 0
            ]);
        }

        $history->increment('biji', $rinse->biji_keluar);
        $history->increment('berat', $rinse->berat_keluar);
    }

    /**
     * Handle the Rinse "updated" event.
     */
    public function updated(Rinse $rinse): void
    {
        if (!$rinse->wasChanged(['biji_keluar', 'berat_keluar'])) {
            return;
        }

        $history = History::where('identifiers_id', $rinse->history->identifiers_id)
            ->where('asal', 'PR07CB')
            ->where('tujuan', 'PR08MC')
            ->first();

        if (!$history) return;

        $history->decrement('biji', $rinse->getOriginal('biji_keluar') ?? 0);
        $history->decrement('berat', $rinse->getOriginal('berat_keluar') ?? 0);

        $history->increment('biji', $rinse->biji_keluar ?? 0);
        $history->increment('berat', $rinse->berat_keluar ?? 0);
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
            empty($rinse->biji_keluar) &&
            empty($rinse->berat_keluar)
        ) {
            $rinse->biji_keluar = 0;
            $rinse->berat_keluar = 0;
        }

        $history = History::where('identifiers_id', $rinse->history->identifiers_id)
            ->where('asal', 'PR07CB')
            ->where('tujuan', 'PR08MC')
            ->first();

        if (!$history) return;

        if (
            ($rinse->biji_keluar ?? 0) > $history->sisa_biji_cuci ||
            ($rinse->berat_keluar ?? 0) > $history->sisa_berat_cuci
        ) {
            throw ValidationException::withMessages([
                'delete' => 'Data tidak dapat dihapus karena stok sudah digunakan'
            ]);
        }

        $history->decrement('biji', $rinse->biji_keluar ?? 0);
        $history->decrement('berat', $rinse->berat_keluar ?? 0);

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
