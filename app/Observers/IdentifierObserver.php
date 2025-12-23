<?php

namespace App\Observers;

use App\Models\History;
use App\Models\ProductIdentifier;

class IdentifierObserver
{
    /**
     * Handle the ProductIdentifier "created" event.
     */
    public function created(ProductIdentifier $productIdentifier): void
    {
        History::create([
            'identifiers_id' => $productIdentifier->id,
            'asal' => 'PR01GB',
            'tujuan' => 'PR02SK',
            'biji' => $productIdentifier->biji ?? 0,
            'berat' => $productIdentifier->berat ?? 0,
            'status' => 0
        ]);
    }

    /**
     * Handle the ProductIdentifier "updated" event.
     */
    public function updated(ProductIdentifier $productIdentifier): void
    {
        $history = History::where('identifiers_id', $productIdentifier->history->identifiers_id)
            ->where('asal', 'PR01GB')
            ->where('tujuan', 'PR02SK')
            ->first();

        if ($history) {
            $history->update([
                'biji'  => $productIdentifier->biji_masuk,
                'berat' => $productIdentifier->berat_masuk,
            ]);
        }
    }

    /**
     * Handle the ProductIdentifier "deleted" event.
     */
    public function deleted(ProductIdentifier $productIdentifier): void
    {
        History::where('identifiers_id', $productIdentifier->history->identifiers_id)
            ->where('asal', 'PR01GB')
            ->where('tujuan', 'PR02SK')
            ->delete();
    }

    /**
     * Handle the ProductIdentifier "deleting" event.
     */
    public function deleting(ProductIdentifier $productIdentifier): void
    {
        $productIdentifier->histories()->delete();
    }

    /**
     * Handle the ProductIdentifier "restored" event.
     */
    public function restored(ProductIdentifier $productIdentifier): void
    {
        //
    }

    /**
     * Handle the ProductIdentifier "force deleted" event.
     */
    public function forceDeleted(ProductIdentifier $productIdentifier): void
    {
        History::where('identifiers_id', $productIdentifier->id)->delete();
    }
}
