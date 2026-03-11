<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Analysis extends Model
{
    protected $fillable = [
        'item',
        'standard',
    ];

    public function certificates()
    {
        return $this->hasMany(AnalysisCertficate::class, 'analysis_id');
    }
}
