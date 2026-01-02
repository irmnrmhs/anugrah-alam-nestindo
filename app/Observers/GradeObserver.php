<?php

namespace App\Observers;

use App\Models\Grade;
use App\Models\ProductIdentifier;
use Illuminate\Support\Facades\DB;

class GradeObserver
{
    /**
     * Handle the Grade "created" event.
     */
    public function created(Grade $grade): void
    {
        //
    }

    /**
     * Handle the Grade "updated" event.
     */
    // public function updated(Grade $grade): void
    // {
    //     // Arrival
    //     if (!$grade->wasChanged('grade')) {
    //         return;
    //     }

    //     DB::transaction(function () use ($grade) {

    //         $identifiers = ProductIdentifier::with([
    //             'rawMaterial.arrival.dcertificate.supplier'
    //         ])->where('grades_id', $grade->id)->get();

    //         foreach ($identifiers as $identifier) {
    //             if (
    //                 !$identifier->rawMaterial ||
    //                 !$identifier->rawMaterial->arrival ||
    //                 !$identifier->rawMaterial->arrival->dcertificate ||
    //                 !$identifier->rawMaterial->arrival->dcertificate->supplier
    //             ) {
    //                 continue;
    //             }

    //             $cleanGrade = preg_replace('/[^A-Za-z0-9]/', '', $grade->grade);
    //             $cleanKode = preg_replace('/[^A-Za-z0-9]/', '', $identifier->rawMaterial->kode);
    //             // $supplier = $identifier->rawMaterial->arrival->dcertificate->supplier->kode;

    //             // $kodeBaru = $cleanGrade . '-' . $cleanKode . $supplier;
    //             $kodeBaru = $cleanGrade . '-' . $cleanKode;

    //             $identifier->update([
    //                 'kode' => $kodeBaru,
    //             ]);
    //         }
    //     });
    // }

    public function updated(Grade $grade): void
    {
        if (!$grade->wasChanged('grade')) {
            return;
        }

        $identifiers = ProductIdentifier::with('rawMaterial')
            ->where('grades_id', $grade->id)
            ->get();

        DB::transaction(function () use ($identifiers, $grade) {
            foreach ($identifiers as $identifier) {

                if (!$identifier->rawMaterial) {
                    continue;
                }

                $cleanGrade = preg_replace('/[^A-Za-z0-9]/', '', $grade->grade);
                $cleanKode  = preg_replace('/[^A-Za-z0-9]/', '', $identifier->rawMaterial->kode);

                $identifier->updateQuietly([
                    'kode' => ProductIdentifier::generateKode(
                        $grade->grade,
                        $identifier->rawMaterial->kode
                    )
                ]);
            }
        });
    }

    /**
     * Handle the Grade "deleted" event.
     */
    public function deleted(Grade $grade): void
    {
        //
    }

    /**
     * Handle the Grade "restored" event.
     */
    public function restored(Grade $grade): void
    {
        //
    }

    /**
     * Handle the Grade "force deleted" event.
     */
    public function forceDeleted(Grade $grade): void
    {
        //
    }
}
