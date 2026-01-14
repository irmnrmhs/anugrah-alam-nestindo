<?php

namespace App\Observers;

use App\Models\Container;
use App\Models\RawMaterial;
use Illuminate\Validation\ValidationException;

class ContainerObserver
{
    /**
     * Handle the Container "created" event.
     */
    public function created(Container $container): void
    {
        $rm = RawMaterial::where('kode', $container->arrival->kode)->first();

        if(!$rm) return;

        $bijiOut = $rm->stocks()->sum('biji');
        $beratOut = $rm->stocks()->sum('berat');

        if($container->biji < $bijiOut){
            throw ValidationException::withMessages([
                'biji' => 'Biji keluar lebih kecil dari stok yang sudah dipakai proses berikutnya'
            ]);
        }

        if($container->berat < $beratOut){
            throw ValidationException::withMessages([
                'berat' => 'Berat keluar lebih kecil dari stok yang sudah dipakai proses berikutnya'
            ]);
        }

        RawMaterial::update([
            'biji' => $container->biji ?? 0,
            'berat' => $container->berat ?? 0,
        ]);
    }

    /**
     * Handle the Container "updated" event.
     */
    public function updated(Container $container): void
    {
        //
    }

    public function updating(Container $container)
    {
        $rm = RawMaterial::where('arrivals_id', $container->arrival->id)->first();

        if(!$rm) return;

        $bijiOut = $rm->stocks()->sum('biji');
        $beratOut = $rm->stocks()->sum('berat');

        if($container->biji < $bijiOut){
            throw ValidationException::withMessages([
                'biji' => 'Biji keluar lebih kecil dari stok yang sudah dipakai proses berikutnya'
            ]);
        }

        if($container->berat < $beratOut){
            throw ValidationException::withMessages([
                'berat' => 'Berat keluar lebih kecil dari stok yang sudah dipakai proses berikutnya'
            ]);
        }
    }

    public function deleting(Container $container)
    {
        $rm = RawMaterial::where('arrivals_id', $container->arrival->id)->first();

        if (!$rm) return;

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
     * Handle the Container "deleted" event.
     */
    public function deleted(Container $container): void
    {
        //
    }

    /**
     * Handle the Container "restored" event.
     */
    public function restored(Container $container): void
    {
        //
    }

    /**
     * Handle the Container "force deleted" event.
     */
    public function forceDeleted(Container $container): void
    {
        //
    }
}
