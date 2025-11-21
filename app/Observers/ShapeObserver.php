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
        $grades = Grade::where('shapes_id', $shape->id)->get();

        foreach ($grades as $grade) {
            $newGrade = strtoupper(
                $shape->kode . '-' .
                $grade->feather->kode . '-' .
                $grade->color->kode
            );

            $grade->update(['grade' => $newGrade]);
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
