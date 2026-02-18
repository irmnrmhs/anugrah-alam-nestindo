<?php

namespace App\Observers;

use App\Models\Container;
use App\Models\RawMaterial;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class ContainerObserver
{
    /**
     * Handle the Container "created" event.
     */
    public function created(Container $container): void
    {
        $rm = RawMaterial::where('kode', $container->arrival->kode)->first();

        if (!$rm) return;

        $rm->biji  += $container->biji;
        $rm->berat += $container->berat;
        $rm->save();
    }

    /**
     * Handle the Container "updated" event.
     */
    public function updated(Container $container): void
    {
        //
    }

    public function updating(Container $container): void
    {
        if (! $container->isDirty(['biji', 'berat'])) {
            return;
        }

        $rm = RawMaterial::where('kode', $container->arrival->kode)->first();
        if (!$rm) return;

        $oldBiji  = $container->getOriginal('biji');
        $oldBerat = $container->getOriginal('berat');

        $deltaBiji  = $container->biji  - $oldBiji;
        $deltaBerat = $container->berat - $oldBerat;

        // validasi stok keluar
        if (
            ($rm->biji + $deltaBiji) < $rm->total_biji_keluar ||
            ($rm->berat + $deltaBerat) < $rm->total_berat_keluar
        ) {
            throw new HttpResponseException(
                response()->json([
                    'status'  => 'error',
                    'message' => 'Perubahan tidak valid, stok sudah digunakan proses lanjutan'
                ], 422)
            );
        }

        $rm->biji  += $deltaBiji;
        $rm->berat += $deltaBerat;
        $rm->save();
    }

    public function deleting(Container $container): void
    {
        $rm = RawMaterial::where('kode', $container->arrival->kode)->first();
        if (!$rm) return;

        $newBiji  = $rm->biji  - $container->biji;
        $newBerat = $rm->berat - $container->berat;

        if (
            $newBiji  < $rm->total_biji_keluar ||
            $newBerat < $rm->total_berat_keluar
        ) {
            throw ValidationException::withMessages([
                'delete' => 'Kontainer tidak dapat dihapus karena stok sudah digunakan'
            ]);
        }

        $rm->biji  = $newBiji;
        $rm->berat = $newBerat;
        $rm->save();
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
