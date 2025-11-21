<?php

namespace App\Observers;

use App\Models\Grade;
use App\Models\Color;

class ColorObserver
{
    /**
     * Handle the Color "created" event.
     */
    public function created(Color $color): void
    {
        //
    }

    /**
     * Handle the Color "updated" event.
     */
    public function updated(Color $color): void
    {
        $grades = Grade::where('colors_id', $color->id)->get();

        foreach ($grades as $grade) {
            $newGrade = strtoupper(
                $grade->shape->kode . '-' .
                $grade->feather->kode . '-' .
                $color->kode
            );

            $grade->update(['grade' => $newGrade]);
        }
    }

    /**
     * Handle the Color "deleted" event.
     */
    public function deleted(Color $color): void
    {
        //
    }

    /**
     * Handle the Color "restored" event.
     */
    public function restored(Color $color): void
    {
        //
    }

    /**
     * Handle the Color "force deleted" event.
     */
    public function forceDeleted(Color $color): void
    {
        //
    }
}
