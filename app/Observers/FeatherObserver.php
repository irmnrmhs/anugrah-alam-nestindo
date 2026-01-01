<?php

namespace App\Observers;

use App\Models\Grade;
use App\Models\Feather;

class FeatherObserver
{
    /**
     * Handle the Feather "created" event.
     */
    public function created(Feather $feather): void
    {
        //
    }

    /**
     * Handle the Feather "updated" event.
     */
    public function updated(Feather $feather): void
    {
        $grades = Grade::with(['shape', 'color'])
            ->where('feathers_id', $feather->id)
            ->get();

        foreach ($grades as $grade) {

            if (!$grade->shape || !$grade->color) {
                continue;
            }

            $grade->grade = strtoupper(
                $grade->shape->kode . '-' .
                $feather->kode . '-' .
                $grade->color->kode
            );

            $grade->saveQuietly();
        }
    }

    /**
     * Handle the Feather "deleted" event.
     */
    public function deleted(Feather $feather): void
    {
        //
    }

    /**
     * Handle the Feather "restored" event.
     */
    public function restored(Feather $feather): void
    {
        //
    }

    /**
     * Handle the Feather "force deleted" event.
     */
    public function forceDeleted(Feather $feather): void
    {
        //
    }
}
