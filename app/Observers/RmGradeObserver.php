<?php

namespace App\Observers;

use App\Models\History;
use App\Models\Product;
use App\Models\GradeColor;
use Illuminate\Validation\ValidationException;

class RmGradeObserver
{
    /**
     * Handle the GradeColor "created" event.
     */
    public function created(GradeColor $gradeColor): void
    {
        if ($gradeColor->biji <= 0 && $gradeColor->berat <= 0) {
            return;
        }

        $history = History::where('gcolors_id', $gradeColor->id)
            ->where('asal', 'PR01GB')
            ->where('tujuan', 'PR02SK')
            ->first();

        if (!$history) {
            $history = History::create([
                'gcolors_id' => $gradeColor->id,
                'asal' => 'PR01GB',
                'tujuan' => 'PR02SK',
                'biji' => 0,
                'berat' => 0,
            ]);
        }

        if ($gradeColor->grade === 'HANCURAN') {
            $history->increment('biji', $gradeColor->berat);
        } else {
            $history->increment('biji', $gradeColor->biji);
        }
    }

    public function updating(gradeColor $gradeColor)
    {
        if(!$gradeColor->isDirty(['biji', 'berat'])){
            return;
        }

        $history = History::where('gcolors_id', $gradeColor->id)
                ->where('asal', 'PR01GB')
                ->where('tujuan', 'PR02SK')
                ->first();

        if(!$history) return;

        $bijiOut = $history->edges()->sum('biji');
        // $beratOut = $history->edges()->sum('berat');
        $newBiji = $gradeColor->grade === 'HANCURAN'
            ? $gradeColor->berat
            : $gradeColor->biji;

        // if($gradeColor->biji < $bijiOut){
        //     throw ValidationException::withMessages([
        //         'biji' => 'Biji keluar lebih kecil dari stok yang sudah dipakai proses berikutnya'
        //     ]);

        if ($newBiji < $bijiOut) {
            throw ValidationException::withMessages([
                'biji' => 'Stok lebih kecil dari yang sudah dipakai proses berikutnya'
            ]);
        }

        // if($gradeColor->berat < $beratOut){
        //     throw ValidationException::withMessages([
        //         'berat' => 'Berat keluar lebih kecil dari stok yang sudah dipakai proses berikutnya'
        //     ]);
        // }
    }
    /**
     * Handle the GradeColor "updated" event.
     */

    public function updated(GradeColor $gradeColor): void
    {
        // 1. sync stok ke history
        if ($gradeColor->wasChanged(['biji', 'berat'])) {

            $history = History::where('gcolors_id', $gradeColor->id)
                ->where('asal', 'PR01GB')
                ->where('tujuan', 'PR02SK')
                ->first();

            if ($history) {

                // nilai lama
                $oldBiji = $gradeColor->getOriginal('grade') === 'HANCURAN'
                    ? $gradeColor->getOriginal('berat')
                    : $gradeColor->getOriginal('biji');

                // nilai baru
                $newBiji = $gradeColor->grade === 'HANCURAN'
                    ? $gradeColor->berat
                    : $gradeColor->biji;

                $history->decrement('biji', $oldBiji ?? 0);
                $history->increment('biji', $newBiji ?? 0);
            }
        }

        // 2. sync grade product
        if ($gradeColor->wasChanged('grade')) {

            $products = Product::whereHas('history', function ($q) use ($gradeColor) {
                $q->where('gcolors_id', $gradeColor->id);
            })->with(['history', 'grade'])->get();

            foreach ($products as $product) {

                if ($product->history->isEmpty() || !$product->grade) {
                    continue;
                }

                $product->updateQuietly([
                    'grade' => Product::generateCode(
                        $product->grade->grade,
                        $gradeColor->grade
                    )
                ]);
            }
        }
    }

    // public function updated(GradeColor $gradeColor): void
    // {
    //     // 1. sync stok ke history
    //     if ($gradeColor->wasChanged(['biji', 'berat'])) {

    //         $history = History::where('gcolors_id', $gradeColor->id)
    //             ->where('asal', 'PR01GB')
    //             ->where('tujuan', 'PR02SK')
    //             ->first();

    //         if ($history) {
    //             $history->decrement('biji', $gradeColor->getOriginal('biji') ?? 0);
    //             // $history->decrement('berat', $gradeColor->getOriginal('berat') ?? 0);

    //             $history->increment('biji', $gradeColor->biji ?? 0);
    //             // $history->increment('berat', $gradeColor->berat ?? 0);
    //         }
    //     }

    //     // 2. sync grade product
    //     if ($gradeColor->wasChanged('grade')) {

    //         $products = Product::whereHas('history', function ($q) use ($gradeColor) {
    //             $q->where('gcolors_id', $gradeColor->id);
    //         })->with(['history', 'grade'])->get();

    //         foreach ($products as $product) {

    //             if ($product->history->isEmpty() || !$product->grade) {
    //                 continue;
    //             }

    //             $product->updateQuietly([
    //                 'grade' => Product::generateCode(
    //                     $product->grade->grade,
    //                     $gradeColor->grade
    //                 )
    //             ]);
    //         }
    //     }
    // }

    public function deleting(GradeColor $gradeColor): void
    {
        $history = History::where('gcolors_id', $gradeColor->id)
            ->where('asal', 'PR01GB')
            ->where('tujuan', 'PR02SK')
            ->first();

        if (!$history) return;
        
        if (
            $history->sisa_biji_sesek < $history->biji
        ) {
            throw new \Exception(
                'Data tidak dapat dihapus karena stok sudah digunakan'
            );
        }

        $history->delete();
    }

    /**
     * Handle the GradeColor "deleted" event.
     */
    public function deleted(GradeColor $gradeColor): void
    {
        //
    }

    /**
     * Handle the GradeColor "restored" event.
     */
    public function restored(GradeColor $gradeColor): void
    {
        //
    }

    /**
     * Handle the GradeColor "force deleted" event.
     */
    public function forceDeleted(GradeColor $gradeColor): void
    {
        //
    }
}
