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
        
        if ($wbhouse->isDirty('kode')) {

            $newKode = $wbhouse->kode;

            $dcerts = Dcertificate::where('wbhouses_id', $wbhouse->id)->get();

            foreach ($dcerts as $dcert) {

                foreach ($dcert->arrivals as $arrival) {

                    $arrival->kode = $newKode . '-' .
                        Carbon::parse($arrival->tgl_kedatangan)->format('dmy');

                    $arrival->save();
                }
            }
        }
    }
}
