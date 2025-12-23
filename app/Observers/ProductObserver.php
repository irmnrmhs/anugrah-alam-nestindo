<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\FinishedProduct;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        FinishedProduct::create([
            'kode' => $product->kode,
            'products_id' => $product->id,
            'biji' => $product->biji ?? 0,
            'berat' => $product->berat ?? 0,
        ]);
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        if ($product->wasChanged('kode')) {
            FinishedProduct::where('products_id', $product->id)
                ->update([
                    'kode' => $product->kode
                ]);
        }
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }
}
