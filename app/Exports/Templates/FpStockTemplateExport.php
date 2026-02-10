<?php

namespace App\Exports;

use App\Models\FpStock;
use Maatwebsite\Excel\Concerns\FromCollection;

class FpStockTemplateExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return FpStock::all();
    }
}
