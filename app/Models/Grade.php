<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = [
        // 'categories_id',
        'shapes_id',
        'feathers_id',
        'colors_id',
        'status'
    ];

    // public function category()
    // {
    //     return $this->belongsTo(Category::class, 'categories_id');
    // }

    public function shape()
    {
        return $this->belongsTo(Shape::class, 'shapes_id');
    }

    public function feather()
    {
        return $this->belongsTo(Feather::class, 'feathers_id');
    }

    public function color()
    {
        return $this->belongsTo(Color::class, 'colors_id');
    }

    public function identifiers()
    {
        return $this->hasMany(ProductIdentifier::class, 'grades_Id');
    }

    public function getGradeAttribute()
    {
        return strtoupper(
            $this->shape?->kode . '-' .
            $this->feather?->kode . '-' .
            $this->color?->kode
        );
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
