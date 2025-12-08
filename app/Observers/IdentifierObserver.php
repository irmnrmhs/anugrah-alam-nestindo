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
            'biji' => $productIdentifier->biji,
            'berat' => $productIdentifier->berat,
            'status' => 0
        ]);
    }

    /**
     * Handle the ProductIdentifier "updated" event.
     */
    public function updated(ProductIdentifier $productIdentifier): void
    {
        // if ($productIdentifier->wasChanged('identifiers_id')) {
        //     History::where('identifiers_id', $productIdentifier->id)
        //         ->update([
        //             'identifiers_id' => $productIdentifier->identifiers_id
        //         ]);
        // }
    }

    /**
     * Handle the ProductIdentifier "deleted" event.
     */
    public function deleted(ProductIdentifier $productIdentifier): void
    {
        //
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
        //
    }
}
