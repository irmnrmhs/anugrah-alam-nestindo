<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\FinishedProduct;
use Illuminate\Validation\ValidationException;

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

    // public function updating(Product $product): void
    // {
    //     FinishedProduct::where('products_id', $product->id)
    //         ->update([
    //             'kode' => $product->kode,
    //             'biji' => $product->biji ?? 0,
    //             'berat' => $product->berat ?? 0,
    //         ]);
    // }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        // if($product->wasChanged(['biji', 'berat'])){
        //     $fp = FinishedProduct::where('products_id', $product->id)->get();

        //     if($fp){
        //         $fp->decrement('biji', $product->getOriginal('biji') ?? 0);
        //         $fp->decrement('berat', $product->getOriginal('berat') ?? 0);

        //         $fp->increment('biji', $product->biji ?? 0);
        //         $fp->increment('berat', $product->berat ?? 0);
        //     }
        // }
    }

    public function updating(Product $product)
    {
        if (!$product->isDirty(['biji', 'berat'])) {
            return;
        }

        $fp = FinishedProduct::where('products_id', $product->id)->get();

        if (!$fp) return;

        $bijiOut = $fp->fpstocks()->sum('biji_keluar');
        $beratOut = $fp->fpstocks()->sum('berat_keluar');

        if ($product->biji < $bijiOut) {
            throw ValidationException::withMessages([
                'biji' => 'Biji keluar lebih kecil dari stok yang sudah dipakai proses berikutnya'
            ]);
        }

        if ($product->berat < $beratOut) {
            throw ValidationException::withMessages([
                'berat' => 'Berat keluar lebih kecil dari stok yang sudah dipakai proses berikutnya'
            ]);
        }
    }

    public function deleting(Product $product): void
    {
        $fp = FinishedProduct::where('products_id', $product->id)->first();

        if (!$fp) return;

        if (
            $fp->biji_sisa < $fp->biji ||
            $fp->berat_sisa < $fp->berat
        ) {
            throw ValidationException::withMessages([
                'delete' => 'Data tidak dapat dihapus karena stok sudah digunakan'
            ]);
        }

        // kalau aman → hapus FinishedProduct juga
        $fp->delete();
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        // $fp = FinishedProduct::where('products_id', $product->id)->first();

        // if (!$fp) return;

        // if (
        //     $fp->biji_sisa < $fp->biji ||
        //     $fp->berat_sisa < $fp->berat
        // ) {
        //     throw new \Exception(
        //         'Data tidak dapat dihapus karena stok sudah digunakan'
        //     );
        // }

        // $fp->delete();
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
