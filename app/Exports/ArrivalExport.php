<?php

namespace App\Exports;

use App\Models\Arrival;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ArrivalExport implements FromView, ShouldAutoSize
{
    protected Arrival $arrival;

    public function __construct(Arrival $arrival)
    {
        $this->arrival = $arrival;
    }

    public function view(): View
    {
        return view('exports.excel.arrival-excel', [
            'arrival' => $this->arrival
        ]);
    }
}
