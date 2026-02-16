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

        $history = History::where('identifiers_id', $gradeColor->id)
            ->where('asal', 'PR01GB')
            ->where('tujuan', 'PR02SK')
            ->first();

        if (!$history) {
            $history = History::create([
                'identifiers_id' => $gradeColor->id,
                'asal' => 'PR01GB',
                'tujuan' => 'PR02SK',
                'biji' => 0,
                'berat' => 0,
            ]);
        }

        $history->increment('biji', $gradeColor->biji);
        $history->increment('berat', $gradeColor->berat);
    }

    public function updating(gradeColor $gradeColor)
    {
        if(!$gradeColor->isDirty(['biji', 'berat'])){
            return;
        }

        $history = History::where('identifiers_id', $gradeColor->id)
                ->where('asal', 'PR01GB')
                ->where('tujuan', 'PR02SK')
                ->first();

        if(!$history) return;

        $bijiOut = $history->edges()->sum('biji');
        $beratOut = $history->edges()->sum('berat');

        if($gradeColor->biji < $bijiOut){
            throw ValidationException::withMessages([
                'biji' => 'Biji keluar lebih kecil dari stok yang sudah dipakai proses berikutnya'
            ]);
        }

        if($gradeColor->berat < $beratOut){
            throw ValidationException::withMessages([
                'berat' => 'Berat keluar lebih kecil dari stok yang sudah dipakai proses berikutnya'
            ]);
        }
    }
    /**
     * Handle the GradeColor "updated" event.
     */
    public function updated(GradeColor $gradeColor): void
    {
        // 1. Sync stok ke history
        if ($gradeColor->wasChanged(['biji', 'berat'])) {

            $history = History::where('identifiers_id', $gradeColor->id)
                ->where('asal', 'PR01GB')
                ->where('tujuan', 'PR02SK')
                ->first();

            if ($history) {
                $history->decrement('biji', $gradeColor->getOriginal('biji') ?? 0);
                $history->decrement('berat', $gradeColor->getOriginal('berat') ?? 0);

                $history->increment('biji', $gradeColor->biji ?? 0);
                $history->increment('berat', $gradeColor->berat ?? 0);
            }
        }

        // 2. Sync kode Product
        if ($gradeColor->wasChanged('kode')) {

            $products = Product::whereHas('history', function ($q) use ($gradeColor) {
                $q->where('identifiers_id', $gradeColor->id);
            })->with(['history', 'grade'])->get();

            foreach ($products as $product) {

                if ($product->history->isEmpty() || !$product->grade) {
                    continue;
                }

                $product->updateQuietly([
                    'kode' => Product::generateCode(
                        $product->grade->kode,
                        $gradeColor->kode
                    )
                ]);
            }
        }
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
