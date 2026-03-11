<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalysisCertficate extends Model
{
    protected $fillable = [
        'fproducts_id',
        'exports_id',
        'analysis_id',
        'tanggal',
        'hasil',
    ];

    public function fproduct()
    {
        return $this->belongsTo(FinishedProduct::class, 'fproducts_id');
    }

    public function export()
    {
        return $this->belongsTo(Export::class, 'exports_id');
    }

    public function analysis()
    {
        return $this->belongsTo(Analysis::class, 'analysis_id');
    }
}
