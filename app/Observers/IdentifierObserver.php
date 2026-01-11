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
        if ($productIdentifier->biji <= 0 && $productIdentifier->berat <= 0) {
            return;
        }

        $history = History::where('identifiers_id', $productIdentifier->id)
            ->where('asal', 'PR01GB')
            ->where('tujuan', 'PR02SK')
            ->first();

        if (!$history) {
            $history = History::create([
                'identifiers_id' => $productIdentifier->id,
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
        /**
         * ==============================
         * 1. UPDATE BIJI / BERAT
         * ==============================
         */
        if ($productIdentifier->wasChanged(['biji', 'berat'])) {

            $history = History::where('identifiers_id', $productIdentifier->id)
                ->where('asal', 'PR01GB')
                ->where('tujuan', 'PR02SK')
                ->first();

            if ($history) {
                $history->decrement('biji', $productIdentifier->getOriginal('biji') ?? 0);
                $history->decrement('berat', $productIdentifier->getOriginal('berat') ?? 0);

                $history->increment('biji', $productIdentifier->biji ?? 0);
                $history->increment('berat', $productIdentifier->berat ?? 0);
            }
        }

        /**
         * ==============================
         * 2. UPDATE KODE PRODUCT
         * ==============================
         */
        if (!$productIdentifier->wasChanged('kode')) {
            return;
        }

        DB::transaction(function () use ($productIdentifier) {

            $products = $productIdentifier->histories()
                ->with('products.grade', 'products.fproduct')
                ->get()
                ->pluck('products')
                ->flatten();

            foreach ($products as $product) {

                $newKode = $product->kode . '-' .
                    preg_replace('/[^A-Za-z0-9]/', '', $productIdentifier->kode);

                $product->updateQuietly([
                    'kode' => $newKode
                ]);
            }
        });
    }

    /**
     * Handle the ProductIdentifier "deleted" event.
     */
    public function deleted(ProductIdentifier $productIdentifier): void
    {
        // $history = History::where('identifiers_id', $productIdentifier->id)
        //     ->where('asal', 'PR01GB')
        //     ->where('tujuan', 'PR02SK')
        //     ->first();

        // if(
        //     $history->biji != $history->sisa_biji_sesek || 
        //     $history->berat != $history->sisa_berat_sesek
        // ){
        //     $productIdentifier->histories()->delete();
        //     $history->increment('biji', $productIdentifier->biji ?? 0);
        //     $history->increment('berat', $productIdentifier->berat ?? 0);
        // }
    }

    /**
     * Handle the ProductIdentifier "deleting" event.
     */
    public function deleting(ProductIdentifier $productIdentifier): void
    {
        // $productIdentifier->histories()->delete();
        $history = History::where('identifiers_id', $productIdentifier->id)
            ->where('asal', 'PR01GB')
            ->where('tujuan', 'PR02SK')
            ->first();

        if (!$history) return;
        
        if (
            $history->sisa_biji_sesek < $history->biji ||
            $history->sisa_berat_sesek < $history->berat
        ) {
            throw new \Exception(
                'Data tidak dapat dihapus karena stok sudah digunakan'
            );
        }

        $history->delete();
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

// <?php

// namespace App\Observers;

// use App\Models\History;
// use App\Models\ProductIdentifier;

// class IdentifierObserver
// {
//     /**
//      * Handle the ProductIdentifier "created" event.
//      */
//     public function created(ProductIdentifier $productIdentifier): void
//     {
//         History::create([
//             'identifiers_id' => $productIdentifier->id,
//             'asal' => 'PR01GB',
//             'tujuan' => 'PR02SK',
//             'biji' => $productIdentifier->biji,
//             'berat' => $productIdentifier->berat,
//             'status' => 0
//         ]);
//     }

//     /**
//      * Handle the ProductIdentifier "updated" event.
//      */
//     public function updated(ProductIdentifier $productIdentifier): void
//     {
//         $history = History::where('identifiers_id', $productIdentifier->history->identifiers_id)
//             ->where('asal', 'PR01GB')
//             ->where('tujuan', 'PR02SK')
//             ->first();

//         if ($history) {
//             $history->update([
//                 'biji'  => $productIdentifier->biji_masuk,
//                 'berat' => $productIdentifier->berat_masuk,
//             ]);
//         }
//     }

//     /**
//      * Handle the ProductIdentifier "deleted" event.
//      */
//     public function deleted(ProductIdentifier $productIdentifier): void
//     {
//         History::where('identifiers_id', $productIdentifier->history->identifiers_id)
//             ->where('asal', 'PR01GB')
//             ->where('tujuan', 'PR02SK')
//             ->delete();
//     }

//     /**
//      * Handle the ProductIdentifier "deleting" event.
//      */
//     public function deleting(ProductIdentifier $productIdentifier): void
//     {
//         $productIdentifier->histories()->delete();
//     }

//     /**
//      * Handle the ProductIdentifier "restored" event.
//      */
//     public function restored(ProductIdentifier $productIdentifier): void
//     {
//         //
//     }

//     /**
//      * Handle the ProductIdentifier "force deleted" event.
//      */
//     public function forceDeleted(ProductIdentifier $productIdentifier): void
//     {
//         History::where('identifiers_id', $productIdentifier->id)->delete();
//     }
// }