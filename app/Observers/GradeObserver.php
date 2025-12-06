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
    public function updated(Grade $grade): void
    {
        // Pastikan hanya memproses jika field "grade" berubah
        if (!$grade->wasChanged('grade')) {
            return;
        }

        DB::transaction(function () use ($grade) {

            // Ambil semua ProductIdentifier yang menggunakan grade tersebut
            $identifiers = ProductIdentifier::with([
                'rawMaterial.arrival.dcertificate.supplier'
            ])->where('grades_id', $grade->id)->get();

            foreach ($identifiers as $identifier) {

                // Skip jika relasi tidak lengkap
                if (
                    !$identifier->rawMaterial ||
                    !$identifier->rawMaterial->arrival ||
                    !$identifier->rawMaterial->arrival->dcertificate ||
                    !$identifier->rawMaterial->arrival->dcertificate->supplier
                ) {
                    continue;
                }

                // Bersihkan karakter selain huruf/angka
                $cleanGrade = preg_replace('/[^A-Za-z0-9]/', '', $grade->grade);
                $cleanKode = preg_replace('/[^A-Za-z0-9]/', '', $identifier->rawMaterial->kode);
                $supplier = $identifier->rawMaterial->arrival->dcertificate->supplier->kode;

                // Bentuk kode baru
                $kodeBaru = $cleanGrade . '-' . $cleanKode . $supplier;

                // Update kode identifier
                $identifier->update([
                    'kode' => $kodeBaru,
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
