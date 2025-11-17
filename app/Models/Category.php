<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'kode',
        'kategori',
        'keterangan',
    ];

    public function suppliers()
    {
        return $this->hasMany(Supplier::class, 'categories_id');
    }

    public function testTypes()
    {
        return $this->hasMany(TestType::class, 'categories_id');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class, 'categories_id');
    }
}
