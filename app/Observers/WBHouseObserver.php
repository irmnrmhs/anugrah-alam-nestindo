<?php

namespace App\Observers;

use App\Models\WBHouse;
use App\Models\Dcertificate;
use App\Models\Arrival;
use Carbon\Carbon;

class WBHouseObserver
{
    public function updated(WBHouse $wbhouse)
    {
        if (!$wbhouse->wasChanged('kode')) {
            return;
        }

        $arrivals = Arrival::whereHas('dcertificate', function ($q) use ($wbhouse) {
            $q->where('wbhouses_id', $wbhouse->id);
        })->get();

        foreach ($arrivals as $arrival) {
            $formatTgl = date('dmy', strtotime($arrival->tgl_kedatangan));

            $arrival->updateQuietly([
                'kode' => $arrival->generateKode()
            ]);
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