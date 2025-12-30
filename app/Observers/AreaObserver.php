<?php

namespace App\Observers;

use App\Models\Area;

class AreaObserver
{
    /**
     * Handle the Area "created" event.
     */
    public function created(Area $area): void
    {
        
    }

    /**
     * Handle the Area "updated" event.
     */
    public function updated(Area $area): void
    {
        if (!$area->wasChanged('kode')) {
            return;
        }

        $wbhouses = $area->wbhouses()->with('dcertificates')->get();

        foreach ($wbhouses as $wbhouse) {
            foreach ($wbhouse->dcertificates as $dcertificate) {

                if (!$dcertificate->no_skp) {
                    continue;
                }

                $parts = explode('/', $dcertificate->no_skp);

                if (count($parts) < 6) {
                    continue;
                }

                $parts[3] = $area->kode;

                $dcertificate->updateQuietly([
                    'no_skp' => implode('/', $parts)
                ]);
            }
        }
    }

    /**
     * Handle the Area "deleted" event.
     */
    public function deleted(Area $area): void
    {
        //
    }

    /**
     * Handle the Area "restored" event.
     */
    public function restored(Area $area): void
    {
        //
    }

    /**
     * Handle the Area "force deleted" event.
     */
    public function forceDeleted(Area $area): void
    {
        //
    }
}
