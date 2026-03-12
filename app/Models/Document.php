<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'depts_id',
        'kode',
        'no',
        'name',
        'rev',
        'tgl',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'depts_id');
    }

    public function details()
    {
        return $this->hasMany(DetailDocument::class, 'documents_id');
    }

    public function getRevFormattedAttribute()
    {
        return str_pad($this->rev, 2, '0', STR_PAD_LEFT);
    }

}
