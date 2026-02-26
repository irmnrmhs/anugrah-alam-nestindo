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
            empty($pull->biji)
        ) {
            $pull->biji = 0;
        }

        $history = History::where('gcolors_id', $pull->history->gcolors_id)
            ->where('asal', 'PR09KC')
            ->where('tujuan', 'PR10PK')
            ->first();

        if (!$history) {
            $history = History::create([
                'gcolors_id' => $pull->history->gcolors_id,
                'asal' => 'PR09KC',
                'tujuan' => 'PR10PK',
                'biji' => 0,
                'berat' => 0,
            ]);
        }

        $history->increment('biji', $pull->biji);
    }

    /**
     * Handle the Pull "updated" event.
     */
    public function updated(Pull $pull): void
    {
        if (!$pull->wasChanged('biji')) {
            return;
        }

        $history = History::where('gcolors_id', $pull->history->gcolors_id)
            ->where('asal', 'PR09KC')
            ->where('tujuan', 'PR10PK')
            ->first();

        if (!$history) return;

        $history->decrement('biji', $pull->getOriginal('biji') ?? 0);

        $history->increment('biji', $pull->biji ?? 0);
    }

    public function updating(Pull $pull)
    {
        if (!$pull->isDirty('biji')) {
            return;
        }

        $history = History::where('gcolors_id', $pull->history->gcolors_id)
            ->where('asal', 'PR09KC')
            ->where('tujuan', 'PR10PK')
            ->first();

        if (!$history) return;

        $dipakaiBiji = $history->dries()->sum('biji');

        if ($pull->biji_keluar < $dipakaiBiji) {
            throw ValidationException::withMessages([
                'biji' => 'Biji lebih kecil dari stok yang sudah dipakai proses berikutnya'
            ]);
        }
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
            empty($pull->biji)
        ) {
            return;
        }

        $history = History::where('gcolors_id', $pull->history->gcolors_id)
            ->where('asal', 'PR09KC')
            ->where('tujuan', 'PR10PK')
            ->first();

        if (!$history) return;

        if (
            ($pull->biji ?? 0) > $history->sisa_biji_kering
        ) {
            throw ValidationException::withMessages([
                'delete' => 'Data tidak dapat dihapus karena stok sudah digunakan'
            ]);
        }

        $history->decrement('biji', $pull->biji ?? 0);

        if ($history->biji <= 0) {
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
