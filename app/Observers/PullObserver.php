<?php

namespace App\Observers;

use App\Models\Pull;
use App\Models\History;
use Illuminate\Validation\ValidationException;

class PullObserver
{
    /**
     * Handle the Pull "created" event.
     */
    public function created(Pull $pull): void
    {
        if (
            empty($pull->biji_keluar) &&
            empty($pull->berat_keluar)
        ) {
            return;
        }

        $history = History::where('identifiers_id', $pull->history->identifiers_id)
            ->where('asal', 'PR09KC')
            ->where('tujuan', 'PR10PK')
            ->first();

        if (!$history) {
            $history = History::create([
                'identifiers_id' => $pull->history->identifiers_id,
                'asal' => 'PR09KC',
                'tujuan' => 'PR10PK',
                'biji' => 0,
                'berat' => 0,
                'status' => 0
            ]);
        }

        $history->increment('biji', $pull->biji_keluar);
        $history->increment('berat', $pull->berat_keluar);
    }

    /**
     * Handle the Pull "updated" event.
     */
    public function updated(Pull $pull): void
    {
        if (!$pull->wasChanged(['biji_keluar', 'berat_keluar'])) {
            return;
        }

        $history = History::where('identifiers_id', $pull->history->identifiers_id)
            ->where('asal', 'PR09KC')
            ->where('tujuan', 'PR10PK')
            ->first();

        if (!$history) return;

        $history->decrement('biji', $pull->getOriginal('biji_keluar') ?? 0);
        $history->decrement('berat', $pull->getOriginal('berat_keluar') ?? 0);

        $history->increment('biji', $pull->biji_keluar ?? 0);
        $history->increment('berat', $pull->berat_keluar ?? 0);
    }

    /**
     * Handle the Pull "deleted" event.
     */
    public function deleted(Pull $pull): void
    {
        // 
    }

    public function deleting(Pull $pull): void
    {
        if (
            empty($pull->biji_keluar) &&
            empty($pull->berat_keluar)
        ) {
            $pull->biji_keluar = 0;
            $pull->berat_keluar = 0;
        }

        $history = History::where('identifiers_id', $pull->history->identifiers_id)
            ->where('asal', 'PR09KC')
            ->where('tujuan', 'PR10PK')
            ->first();

        if (!$history) return;

        if (
            ($pull->biji_keluar ?? 0) > $history->sisa_biji_cuci ||
            ($pull->berat_keluar ?? 0) > $history->sisa_berat_cuci
        ) {
            throw ValidationException::withMessages([
                'delete' => 'Data tidak dapat dihapus karena stok sudah digunakan'
            ]);
        }

        $history->decrement('biji', $pull->biji_keluar ?? 0);
        $history->decrement('berat', $pull->berat_keluar ?? 0);

        if ($history->biji <= 0 && $history->berat <= 0) {
            $history->delete();
        }
    }

    /**
     * Handle the Pull "restored" event.
     */
    public function restored(Pull $pull): void
    {
        //
    }

    /**
     * Handle the Pull "force deleted" event.
     */
    public function forceDeleted(Pull $pull): void
    {
        //
    }
}
