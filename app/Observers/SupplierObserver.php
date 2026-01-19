<?php

namespace App\Observers;

use Carbon\Carbon;
use App\Models\Supplier;
use App\Models\ProductIdentifier;

class SupplierObserver
{
    /**
     * Handle the Supplier "created" event.
     */
    public function created(Supplier $supplier): void
    {
        //
    }

    /**
     * Handle the Supplier "updated" event.
     */
    public function updated(Supplier $supplier)
    {
        if (! $supplier->wasChanged('kode')) {
            return;
        }

        $identifiers = ProductIdentifier::whereHas('rawMaterial.arrival.dcertificate.supplier', function ($q) use ($supplier) {
            $q->where('id', $supplier->id);
        })
        ->with(['rawMaterial.arrival.dcertificate.supplier', 'grade'])
        ->get();

        foreach ($identifiers as $identifier) {

            $cleanGrade = preg_replace('/[^A-Za-z0-9]/', '', $identifier->grade->grade);
            $cleanKode  = preg_replace('/[^A-Za-z0-9]/', '', $identifier->rawMaterial->kode);

            // SESUAI controller
            $newKode = $cleanGrade . '-' . $cleanKode . $supplier->kode;

            if ($identifier->kode !== $newKode) {
                $identifier->update([
                    'kode' => $newKode
                ]);
            }
        }

        // if (! $supplier->wasChanged('kode')) {
        //     return;
        // }

        // $identifiers = ProductIdentifier::whereHas(
        //     'rawMaterial.arrival.dcertificate.supplier',
        //     fn ($q) => $q->where('id', $supplier->id)
        // )
        // ->with(['rawMaterial', 'grade'])
        // ->get();

        // foreach ($identifiers as $identifier) {

        //     $newKode = ProductIdentifier::generateKodeFromSupplier(
        //         $identifier->grade->grade,
        //         $identifier->rawMaterial->kode,
        //         $supplier->kode
        //     );

        //     if ($identifier->kode !== $newKode) {
        //         $identifier->forceFill([
        //             'kode' => $newKode
        //         ])->saveQuietly();
        //     }
        // }
    }

    /**
     * Handle the Supplier "deleted" event.
     */
    public function deleted(Supplier $supplier): void
    {
        //
    }

    /**
     * Handle the Supplier "restored" event.
     */
    public function restored(Supplier $supplier): void
    {
        //
    }

    /**
     * Handle the Supplier "force deleted" event.
     */
    public function forceDeleted(Supplier $supplier): void
    {
        //
    }
}
