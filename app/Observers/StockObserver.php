<?php

namespace App\Observers;

use App\Models\RmStock;
use App\Models\RawMaterial;
use Illuminate\Validation\ValidationException;

class StockObserver
{
    /**
     * Handle the RmStock "created" event.
     */
    public function created(RmStock $rmStock): void
    {
        //
    }

    /**
     * Handle the RmStock "updated" event.
     */
    public function updated(RmStock $rmStock): void
    {
        //
    }

    public function updating(RmStock $rmStock)
    {
        if(!$rmStock->isDirty(['biji_keluar', 'berat_keluar'])){
            return;
        }

        $rm = RawMaterial::where('id', $rmStock->rms_id)->first();

        if(!$rm) return;

        $bijiOut = $rm->identifiers()->sum('biji');
        $beratOut = $rm->identifiers()->sum('berat');

        if($rmStock->biji_keluar < $bijiOut){
            throw ValidationException::withMessages([
                'biji' => 'Biji keluar lebih kecil dari stok yang sudah dipakai proses berikutnya'
            ]);
        }

        if($rm->berat_keluar < $beratOut){
            throw ValidationException::withMessages([
                'berat' => 'Berat keluar lebih kecil dari stok yang sudah dipakai proses berikutnya'
            ]);
        }
    }

    public function deleting(RmStock $rmStock): void
    {
        $rm = RawMaterial::where('id', $rmStock->rms_id)->first();

        if(!$rm) return;

        if (
            $rm->biji_sisa < $rm->biji ||
            $rm->berat_sisa < $rm->berat
        ) {
            throw ValidationException::withMessages([
                'delete' => 'Data tidak dapat dihapus karena stok sudah digunakan'
            ]);
        }

        $rm->delete();
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
