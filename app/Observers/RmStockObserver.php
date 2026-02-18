<?php

namespace App\Observers;

use App\Models\RmStock;
use Illuminate\Http\Exceptions\HttpResponseException;

class RmStockObserver
{
    /**
     * Handle the RmStock "created" event.
     */
    public function created(RmStock $rmStock): void
    {
        //
    }

    public function creating(RmStock $stock): void
    {
        // $raw = $stock->rawMaterial;

        // $totalBijiKeluar = $raw->stocks()->sum('biji');
        // $totalBeratKeluar = $raw->stocks()->sum('berat');

        // $totalBijiIdentifier = $raw->identifiers()->sum('biji');
        // $totalBeratIdentifier = $raw->identifiers()->sum('berat');

        // $bijiTersedia =
        //     $raw->biji
        //     - $totalBijiKeluar
        //     - $totalBijiIdentifier;

        // $beratTersedia =
        //     $raw->berat
        //     - $totalBeratKeluar
        //     - $totalBeratIdentifier;

        // if (
        //     $stock->biji > $bijiTersedia ||
        //     $stock->berat > $beratTersedia
        // ) {
        //     throw ValidationException::withMessages([
        //         'stok' => 'Melebihi stok bahan baku yang tersedia'
        //     ]);
        // }
    }

    /**
     * Saat stok keluar diubah
     */
    public function updating(RmStock $stock): void
    {
        $raw = $stock->rawMaterial;

        $totalBijiKeluar = $raw->stocks()
            ->where('id', '!=', $stock->id)
            ->sum('biji');

        $totalBeratKeluar = $raw->stocks()
            ->where('id', '!=', $stock->id)
            ->sum('berat');

        $totalBijiIdentifier = $raw->identifiers()->sum('biji');
        $totalBeratIdentifier = $raw->identifiers()->sum('berat');

        $bijiTersedia =
            $raw->biji
            - $totalBijiKeluar
            - $totalBijiIdentifier;

        $beratTersedia =
            $raw->berat
            - $totalBeratKeluar
            - $totalBeratIdentifier;

        if (
            $stock->biji > $bijiTersedia ||
            $stock->berat > $beratTersedia
        ) {
            throw new HttpResponseException(
                response()->json([
                    'status'  => 'error',
                    'message' => 'Perubahan tidak valid, stok sudah digunakan proses lanjutan'
                ], 422)
            );
        }
    }

    /**
     * Handle the RmStock "updated" event.
     */
    public function updated(RmStock $rmStock): void
    {
        //
    }

    /**
     * Handle the RmStock "deleted" event.
     */
    public function deleted(RmStock $rmStock): void
    {
        //
    }

    /**
     * Handle the RmStock "restored" event.
     */
    public function restored(RmStock $rmStock): void
    {
        //
    }

    /**
     * Handle the RmStock "force deleted" event.
     */
    public function forceDeleted(RmStock $rmStock): void
    {
        //
    }
}
