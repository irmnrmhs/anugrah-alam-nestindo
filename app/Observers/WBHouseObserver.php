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
        // Jalankan hanya jika 'kode' berubah
        if ($wbhouse->isDirty('kode')) {

            $newKode = $wbhouse->kode;

            // ambil seluruh dcertificate terkait dg wbhouse ini
            $dcerts = Dcertificate::where('wbhouses_id', $wbhouse->id)->get();

            foreach ($dcerts as $dcert) {

                // ambil semua arrival berdasarkan dcertificate
                foreach ($dcert->arrivals as $arrival) {

                    // regenerasi kode arrival
                    $arrival->kode = $newKode . '-' .
                        Carbon::parse($arrival->tgl_kedatangan)->format('dmy');

                    // simpan pakai Eloquent → memicu ArrivalObserver@updated
                    $arrival->save();
                }
            }
        }
    }
}
