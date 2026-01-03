<?php

namespace App\Observers;

use App\Models\Entry;
use App\Models\History;
use Illuminate\Validation\ValidationException;

class EntryObserver
{
    /**
     * Handle the Entry "created" event.
     */
    public function created(Entry $entry): void
    {
        if (
            empty($entry->biji_keluar) &&
            empty($entry->berat_keluar)
        ) {
            $entry->biji_keluar = 0;
            $entry->berat_keluar = 0;
        }

        $history = History::where('identifiers_id', $entry->history->identifiers_id)
            ->where('asal', 'PR08MC')
            ->where('tujuan', 'PR09KC')
            ->first();

        if (!$history) {
            $history = History::create([
                'identifiers_id' => $entry->history->identifiers_id,
                'asal' => 'PR08MC',
                'tujuan' => 'PR09KC',
                'biji' => 0,
                'berat' => 0,
                'status' => 0
            ]);
        }

        $history->increment('biji', $entry->biji_keluar);
        $history->increment('berat', $entry->berat_keluar);
    }

    /**
     * Handle the Entry "updated" event.
     */
    public function updated(Entry $entry): void
    {
        if (!$entry->wasChanged(['biji_keluar', 'berat_keluar'])) {
            return;
        }

        $history = History::where('identifiers_id', $entry->history->identifiers_id)
            ->where('asal', 'PR08MC')
            ->where('tujuan', 'PR09KC')
            ->first();

        if (!$history) return;

        $history->decrement('biji', $entry->getOriginal('biji_keluar') ?? 0);
        $history->decrement('berat', $entry->getOriginal('berat_keluar') ?? 0);

        $history->increment('biji', $entry->biji_keluar ?? 0);
        $history->increment('berat', $entry->berat_keluar ?? 0);
    }

    /**
     * Handle the Entry "deleted" event.
     */
    public function deleted(Entry $entry): void
    {
        // 
    }

    public function deleting(Entry $entry): void
    {
        if (
            empty($entry->biji_keluar) &&
            empty($entry->berat_keluar)
        ) {
            $entry->biji_keluar = 0;
            $entry->berat_keluar = 0;
        }

        $history = History::where('identifiers_id', $entry->history->identifiers_id)
            ->where('asal', 'PR03PC')
            ->where('tujuan', 'PR04IK')
            ->first();

        if (!$history) return;

        if (
            ($entry->biji_keluar ?? 0) > $history->sisa_biji_cuci ||
            ($entry->berat_keluar ?? 0) > $history->sisa_berat_cuci
        ) {
            throw ValidationException::withMessages([
                'delete' => 'Data tidak dapat dihapus karena stok sudah digunakan'
            ]);
        }

        $history->decrement('biji', $entry->biji_keluar ?? 0);
        $history->decrement('berat', $entry->berat_keluar ?? 0);

        if ($history->biji <= 0 && $history->berat <= 0) {
            $history->delete();
        }
    }

    /**
     * Handle the Entry "restored" event.
     */
    public function restored(Entry $entry): void
    {
        //
    }

    /**
     * Handle the Entry "force deleted" event.
     */
    public function forceDeleted(Entry $entry): void
    {
        //
    }
}
