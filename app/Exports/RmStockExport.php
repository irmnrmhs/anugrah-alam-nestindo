<?php

namespace App\Exports;

use App\Models\RmStock;
use Maatwebsite\Excel\Concerns\FromCollection;

class RmStockExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return RmStock::all();
    }
}
