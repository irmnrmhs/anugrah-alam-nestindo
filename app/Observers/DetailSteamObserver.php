<?php

namespace App\Observers;

use App\Models\DetailSteam;
use App\Models\FinishedProduct;
use Illuminate\Validation\ValidationException;

class DetailSteamObserver
{
    /**
     * Handle the Steam "created" event.
     */
    public function created(DetailSteam $dsteam): void
    {
        FinishedProduct::create([
            'kode' => $dsteam->batch,
            'products_id' => $dsteam->id,
            'biji' => $dsteam->biji ?? 0,
            'berat' => $dsteam->berat ?? 0,
        ]);
    }

    public function updating(DetailSteam $dsteam)
    {
        if (!$dsteam->isDirty(['biji', 'berat'])) {
            return;
        }

        $fp = FinishedProduct::where('products_id', $dsteam->id)->first();

        if (!$fp) return;

        $bijiOut = $fp->fpstocks()->sum('biji_keluar');
        $beratOut = $fp->fpstocks()->sum('berat_keluar');

        if ($dsteam->biji < $bijiOut) {
            throw ValidationException::withMessages([
                'biji' => 'Biji keluar lebih kecil dari stok yang sudah dipakai proses berikutnya'
            ]);
        }

        if ($dsteam->berat < $beratOut) {
            throw ValidationException::withMessages([
                'berat' => 'Berat keluar lebih kecil dari stok yang sudah dipakai proses berikutnya'
            ]);
        }
    }
    /**
     * Handle the DetailSteam "updated" event.
     */
    public function updated(DetailSteam $dsteam): void
    {
        //
    }

    public function deleting(DetailSteam $dsteam): void
    {
        $fp = FinishedProduct::where('products_id', $dsteam->id)->first();

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
     * Handle the DetailSteam "deleted" event.
     */
    public function deleted(DetailSteam $dsteam): void
    {
        //
    }

    /**
     * Handle the DetailSteam "restored" event.
     */
    public function restored(DetailSteam $dsteam): void
    {
        //
    }

    /**
     * Handle the DetailSteam "force deleted" event.
     */
    public function forceDeleted(DetailSteam $dsteam): void
    {
        //
    }
}
