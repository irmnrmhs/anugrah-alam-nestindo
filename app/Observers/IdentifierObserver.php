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
        if (!$productIdentifier->isDirty(['biji_keluar', 'berat_keluar'])) {
            return;
        }

        $history = History::where('identifiers_id', $productIdentifier->history->identifiers_id)
            ->where('asal', 'PR01GB')
            ->where('tujuan', 'PR02SK')
            ->first();

        if (!$history) return;

        $history->decrement('biji', $productIdentifier->getOriginal('biji_keluar') ?? 0);
        $history->decrement('berat', $productIdentifier->getOriginal('berat_keluar') ?? 0);

        $history->increment('biji', $productIdentifier->biji_keluar ?? 0);
        $history->increment('berat', $productIdentifier->berat_keluar ?? 0);
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

        if (!$history) return;

        $history->decrement('biji', $productIdentifier->biji_keluar ?? 0);
        $history->decrement('berat', $productIdentifier->berat_keluar ?? 0);

        if ($history->biji <= 0 && $history->berat <= 0) {
            $history->delete();
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
