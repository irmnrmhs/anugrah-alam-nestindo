<?php

namespace App\Observers;

use App\Models\History;
use App\Models\Product;
use App\Models\ProductIdentifier;
use Illuminate\Validation\ValidationException;

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
    public function updated(ProductIdentifier $pi): void
    {
        // 1. Sync stok ke history
        if ($pi->wasChanged(['biji', 'berat'])) {

            $history = History::where('identifiers_id', $pi->id)
                ->where('asal', 'PR01GB')
                ->where('tujuan', 'PR02SK')
                ->first();

            if ($history) {
                $history->decrement('biji', $pi->getOriginal('biji') ?? 0);
                $history->decrement('berat', $pi->getOriginal('berat') ?? 0);

                $history->increment('biji', $pi->biji ?? 0);
                $history->increment('berat', $pi->berat ?? 0);
            }
        }

        // 2. Sync kode Product
        if ($pi->wasChanged('kode')) {

            $products = Product::whereHas('history', function ($q) use ($pi) {
                $q->where('identifiers_id', $pi->id);
            })->with(['history', 'grade'])->get();

            foreach ($products as $product) {

                if ($product->history->isEmpty() || !$product->grade) {
                    continue;
                }

                $product->updateQuietly([
                    'kode' => Product::generateCode(
                        $product->grade->kode,
                        $pi->kode
                    )
                ]);
            }
        }
    }

    public function updating(ProductIdentifier $pi)
    {
        if(!$pi->isDirty(['biji', 'berat'])){
            return;
        }

        $history = History::where('identifiers_id', $pi->id)
                ->where('asal', 'PR01GB')
                ->where('tujuan', 'PR02SK')
                ->first();

        if(!$history) return;

        $bijiOut = $history->edges()->sum('biji_masuk');
        $beratOut = $history->edges()->sum('berat_masuk');

        if($pi->biji < $bijiOut){
            throw ValidationException::withMessages([
                'biji_keluar' => 'Biji keluar lebih kecil dari stok yang sudah dipakai proses berikutnya'
            ]);
        }

        if($pi->berat < $beratOut){
            throw ValidationException::withMessages([
                'berat_keluar' => 'Berat keluar lebih kecil dari stok yang sudah dipakai proses berikutnya'
            ]);
        }
    }

// before
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