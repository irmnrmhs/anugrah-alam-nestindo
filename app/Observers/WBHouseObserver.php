<?php

namespace App\Observers;

use Carbon\Carbon;
use App\Models\Arrival;
use App\Models\WBHouse;
use App\Models\RawMaterial;
use App\Models\Dcertificate;
use Illuminate\Support\Facades\DB;

class WBHouseObserver
{
    // public function updated(WBHouse $wbhouse)
    // {
    //     if (!$wbhouse->wasChanged('kode')) {
    //         return;
    //     }

    //     $arrivals = Arrival::whereHas('dcertificate', function ($q) use ($wbhouse) {
    //         $q->where('wbhouses_id', $wbhouse->id);
    //     })->get();

    //     DB::transaction(function () use ($arrivals) {
    //         foreach ($arrivals as $arrival) {
    //             $arrival->update([
    //                 'kode' => $arrival->generateKode()
    //             ]);
    //         }
    //     });
    // }

    public function updated(WBHouse $wbhouse)
    {
        // $oldPrefix = $wbhouse->getOriginal('kode');
        // $newPrefix = $wbhouse->kode;

        $arrivals = Arrival::whereHas('dcertificate', fn ($q) =>
            $q->where('wbhouses_id', $wbhouse->id)
        )->get();

        foreach ($arrivals as $arrival) {
            $oldKode = $arrival->kode;
            $newKode = $arrival->generateKode();

            $arrival->updateQuietly(['kode' => $newKode]);

            RawMaterial::where('kode', $oldKode)
                ->update(['kode' => $newKode]);
        }
    }
    // public function updated(WBHouse $wbhouse): void
    // {
    //     if (!$wbhouse->isDirty('kode')) {
    //         return;
    //     }

    //     $newKode = $wbhouse->kode;

    //     $dcerts = Dcertificate::with('arrivals')
    //         ->where('wbhouses_id', $wbhouse->id)
    //         ->get();

    //     foreach ($dcerts as $dcert) {

    //         if ($dcert->arrivals->isEmpty()) {
    //             continue;
    //         }

    //         foreach ($dcert->arrivals as $arrival) {

    //             if (!$arrival->tgl_kedatangan) {
    //                 continue;
    //             }

    //             $arrival->kode = $newKode . '-' .
    //                 Carbon::parse($arrival->tgl_kedatangan)->format('dmy');

    //             $arrival->saveQuietly();
    //         }
    //     }
    // }
}