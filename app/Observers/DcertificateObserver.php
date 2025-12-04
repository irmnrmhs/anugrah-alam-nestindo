<?php

namespace App\Observers;

use App\Models\Arrival;
use App\Models\Dcertificate;

class DcertificateObserver
{
    /**
     * Handle the Dcertificate "created" event.
     */
    public function created(Dcertificate $dcertificate): void
    {
        //
    }

    /**
     * Handle the Dcertificate "updated" event.
     */
    public function updated(Dcertificate $dcertificate): void
    {
        $arrivals = Arrival::where('dcertificates_id', $dcertificate->id)->get();
        
        foreach ($arrivals as $arrival){
            $newCode = $dcertificate->wbhouse->kode . '-' . date('dmy', strtotime($arrival->tgl_kedatangan));

            $arrival->update(['kode' => $newCode]);
        }

        // if ($dcertificate->wasChanged('wbhouses_id')) {

        //     $dcertificate->load('wbhouse');

        //     $arrivals = Arrival::where('dcertificates_id', $dcertificate->id)->get();

        //     foreach ($arrivals as $arrival) {
        //         $newCode = $dcertificate->wbhouse->kode . '-' . $arrival->tgl_kedatangan;

        //         $arrival->update(['arrival' => $newCode]); // pastikan nama kolom
        //     }
        // }
    }

    /**
     * Handle the Dcertificate "deleted" event.
     */
    public function deleted(Dcertificate $dcertificate): void
    {
        //
    }

    /**
     * Handle the Dcertificate "restored" event.
     */
    public function restored(Dcertificate $dcertificate): void
    {
        //
    }

    /**
     * Handle the Dcertificate "force deleted" event.
     */
    public function forceDeleted(Dcertificate $dcertificate): void
    {
        //
    }
}
