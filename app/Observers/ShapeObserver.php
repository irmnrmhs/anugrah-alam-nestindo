<?php

namespace App\Observers;

use App\Models\Grade;
use App\Models\Shape;

class ShapeObserver
{
    /**
     * Handle the Shape "created" event.
     */
    public function created(Shape $shape): void
    {
        //
    }

    /**
     * Handle the Shape "updated" event.
     */
    public function updated(Shape $shape): void
    {
        $grades = Grade::with(['feather', 'color'])
            ->where('shapes_id', $shape->id)
            ->get();

        foreach ($grades as $grade) {

            if (!$grade->feather || !$grade->color) {
                continue;
            }

            $grade->grade = strtoupper(
                $shape->kode . '-' .
                $grade->feather->kode . '-' .
                $grade->color->kode
            );

            $grade->saveQuietly();
        }
    }

    /**
     * Handle the Shape "deleted" event.
     */
    public function deleted(Shape $shape): void
    {
        //
    }

    /**
     * Handle the Shape "restored" event.
     */
    public function restored(Shape $shape): void
    {
        //
    }

    /**
     * Handle the Shape "force deleted" event.
     */
    public function forceDeleted(Shape $shape): void
    {
        //
    }
}
