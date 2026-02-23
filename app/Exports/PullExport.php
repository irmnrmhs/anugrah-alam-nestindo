<?php

namespace App\Exports;

use App\Models\Pull;
use Maatwebsite\Excel\Concerns\FromCollection;

class PullExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Pull::all();
    }
}
