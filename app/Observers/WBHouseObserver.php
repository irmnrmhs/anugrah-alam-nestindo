<?php

namespace App\Observers;

use App\Models\WBHouse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WBHouseObserver
{
    /**
     * Handle the WBHouse "created" event.
     */
    public function created(WBHouse $wBHouse): void
    {
        //
    }

    /**
     * Handle the WBHouse "updated" event.
     */
    public function updated(WBHouse $wBHouse): void
    {
        //
    }

    public function updating(WBHouse $wbhouse)
    {
        // Jalankan hanya jika field 'kode' berubah
        if ($wbhouse->isDirty('kode')) {

            $newKode = $wbhouse->kode;

            // Ambil semua dcertificates milik wbhouse ini
            $dcertIds = DB::table('dcertificates')
                ->where('wbhouses_id', $wbhouse->id)
                ->pluck('id');

            if ($dcertIds->isEmpty()) {
                return; // tidak ada arrival yang terkait
            }

            // Ambil semua arrival yang memakai SKP tersebut
            $arrivals = DB::table('arrivals')
                ->whereIn('dcertificates_id', $dcertIds)
                ->get();

            foreach ($arrivals as $arrival) {
                $tgl = Carbon::parse($arrival->tgl_kedatangan)->format('dmy');
                $kodeBaru = $newKode . '-' . $tgl;

                DB::table('arrivals')
                    ->where('id', $arrival->id)
                    ->update(['kode' => $kodeBaru]);
            }
        }
    }

    /**
     * Handle the WBHouse "deleted" event.
     */
    public function deleted(WBHouse $wBHouse): void
    {
        //
    }

    /**
     * Handle the WBHouse "restored" event.
     */
    public function restored(WBHouse $wBHouse): void
    {
        //
    }

    /**
     * Handle the WBHouse "force deleted" event.
     */
    public function forceDeleted(WBHouse $wBHouse): void
    {
        //
    }
}