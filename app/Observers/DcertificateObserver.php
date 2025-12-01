<?php

namespace App\Observers;

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
        //
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
