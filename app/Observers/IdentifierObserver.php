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
        if (
            empty($productIdentifier->biji) &&
            empty($productIdentifier->berat)
        ) {
            return;
        }

        $history = History::where('identifiers_id', $productIdentifier->histories->identifiers_id)
            ->where('asal', 'PR01GB')
            ->where('tujuan', 'PR02SK')
            ->first();

        if (!$history) {
            $history = History::create([
                'identifiers_id' => $productIdentifier->histories->identifiers_id,
                'asal' => 'PR01GB',
                'tujuan' => 'PR02SK',
                'biji' => 0,
                'berat' => 0,
                'status' => 0
            ]);
        }

        $history->increment('biji', $productIdentifier->biji);
        $history->increment('berat', $productIdentifier->berat);
    }

    /**
     * Handle the ProductIdentifier "updated" event.
     */
    public function updated(ProductIdentifier $productIdentifier): void
    {
        if (!$productIdentifier->wasChanged(['biji', 'berat'])) {
            return;
        }

        $history = History::where('identifiers_id', $productIdentifier->histories->identifiers_id)
            ->where('asal', 'PR01GB')
            ->where('tujuan', 'PR02SK')
            ->first();

        if (!$history) return;

        $history->decrement('biji', $productIdentifier->getOriginal('biji') ?? 0);
        $history->decrement('berat', $productIdentifier->getOriginal('berat') ?? 0);

        $history->increment('biji', $productIdentifier->biji ?? 0);
        $history->increment('berat', $productIdentifier->berat ?? 0);
    }

    /**
     * Handle the ProductIdentifier "deleted" event.
     */
    public function deleted(ProductIdentifier $productIdentifier): void
    {
        if (
            empty($productIdentifier->biji) &&
            empty($productIdentifier->berat)
        ) {
            return;
        }

        $history = History::where('identifiers_id', $productIdentifier->histories->identifiers_id)
            ->where('asal', 'PR01GB')
            ->where('tujuan', 'PR02SK')
            ->first();

        if (!$history) return;

        $history->decrement('biji', $productIdentifier->biji ?? 0);
        $history->decrement('berat', $productIdentifier->berat ?? 0);

        if ($history->biji <= 0 && $history->berat <= 0) {
            $history->delete();
        }
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
