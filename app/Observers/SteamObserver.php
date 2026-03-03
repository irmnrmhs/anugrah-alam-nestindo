<?php

namespace App\Observers;

use App\Models\Steam;
use App\Models\FinishedProduct;
use Illuminate\Validation\ValidationException;

class SteamObserver
{
    /**
     * Handle the Steam "created" event.
     */
    public function created(Steam $steam): void
    {
        FinishedProduct::create([
            'kode' => $steam->kode,
            'products_id' => $steam->id,
            'biji' => $steam->biji ?? 0,
            'berat' => $steam->berat ?? 0,
        ]);
    }

    public function updating(Steam $steam)
    {
        if (!$steam->isDirty(['biji', 'berat'])) {
            return;
        }

        $fp = FinishedProduct::where('products_id', $steam->id)->first();

        if (!$fp) return;

        $bijiOut = $fp->fpstocks()->sum('biji_keluar');
        $beratOut = $fp->fpstocks()->sum('berat_keluar');

        if ($steam->biji < $bijiOut) {
            throw ValidationException::withMessages([
                'biji' => 'Biji keluar lebih kecil dari stok yang sudah dipakai proses berikutnya'
            ]);
        }

        if ($steam->berat < $beratOut) {
            throw ValidationException::withMessages([
                'berat' => 'Berat keluar lebih kecil dari stok yang sudah dipakai proses berikutnya'
            ]);
        }
    }
    /**
     * Handle the Steam "updated" event.
     */
    public function updated(Steam $steam): void
    {
        //
    }

    public function deleting(Steam $steam): void
    {
        $fp = FinishedProduct::where('products_id', $steam->id)->first();

        if (!$fp) return;

        if (
            $fp->biji_sisa < $fp->biji ||
            $fp->berat_sisa < $fp->berat
        ) {
            throw ValidationException::withMessages([
                'delete' => 'Data tidak dapat dihapus karena stok sudah digunakan'
            ]);
        }

        $fp->delete();
    }

    /**
     * Handle the Steam "deleted" event.
     */
    public function deleted(Steam $steam): void
    {
        //
    }

    /**
     * Handle the Steam "restored" event.
     */
    public function restored(Steam $steam): void
    {
        //
    }

    /**
     * Handle the Steam "force deleted" event.
     */
    public function forceDeleted(Steam $steam): void
    {
        //
    }
}
