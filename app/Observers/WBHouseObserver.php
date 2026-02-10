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
    public function updated(WBHouse $wbhouse)
    {
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
}