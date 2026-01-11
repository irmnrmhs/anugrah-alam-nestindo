<?php

namespace App\Observers;

use App\Models\FpGrade;

class FpObserver
{
    /**
     * Handle the FpGrade "created" event.
     */
    public function created(FpGrade $fpGrade): void
    {
        //
    }

    /**
     * Handle the FpGrade "updated" event.
     */
    public function updated(FpGrade $fpGrade): void
    {
        //
    }

    /**
     * Handle the FpGrade "deleted" event.
     */
    public function deleted(FpGrade $fpGrade): void
    {
        //
    }

    /**
     * Handle the FpGrade "restored" event.
     */
    public function restored(FpGrade $fpGrade): void
    {
        //
    }

    /**
     * Handle the FpGrade "force deleted" event.
     */
    public function forceDeleted(FpGrade $fpGrade): void
    {
        //
    }
}
