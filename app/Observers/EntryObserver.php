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
            empty($entry->biji)
        ) {
            $entry->biji = 0;
        }

        $history = History::where('gcolors_id', $entry->history->gcolors_id)
            ->where('asal', 'PR08MC')
            ->where('tujuan', 'PR09KC')
            ->first();

        if (!$history) {
            $history = History::create([
                'gcolors_id' => $entry->history->gcolors_id,
                'asal' => 'PR08MC',
                'tujuan' => 'PR09KC',
                'biji' => 0,
                'berat' => 0,
            ]);
        }

        $history->increment('biji', $entry->biji);
    }

    /**
     * Handle the Entry "updated" event.
     */
    public function updated(Entry $entry): void
    {
        if (!$entry->wasChanged(['biji'])) {
            return;
        }

        $history = History::where('gcolors_id', $entry->history->gcolors_id)
            ->where('asal', 'PR08MC')
            ->where('tujuan', 'PR09KC')
            ->first();

        if (!$history) return;

        $history->decrement('biji', $entry->getOriginal('biji') ?? 0);

        $history->increment('biji', $entry->biji ?? 0);
    }

    public function updating(Entry $entry)
    {
        if (!$entry->isDirty(['biji'])) {
            return;
        }

        $history = History::where('gcolors_id', $entry->history->gcolors_id)
            ->where('asal', 'PR08MC')
            ->where('tujuan', 'PR09KC')
            ->first();

        if (!$history) return;

        $dipakaiBiji = $history->pulls()->sum('biji');

        if ($entry->biji < $dipakaiBiji) {
            throw ValidationException::withMessages([
                'biji' => 'Biji keluar lebih kecil dari stok yang sudah dipakai proses berikutnya'
            ]);
        }
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
            empty($entry->biji)
        ) {
            return;
        }

        $history = History::where('gcolors_id', $entry->history->gcolors_id)
            ->where('asal', 'PR08MC')
            ->where('tujuan', 'PR09KC')
            ->first();

        if (!$history) return;

        if (
            ($entry->biji ?? 0) > $history->sisa_biji_keluar
        ) {
            throw ValidationException::withMessages([
                'delete' => 'Data tidak dapat dihapus karena stok sudah digunakan'
            ]);
        }

        $history->decrement('biji', $entry->biji ?? 0);

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
