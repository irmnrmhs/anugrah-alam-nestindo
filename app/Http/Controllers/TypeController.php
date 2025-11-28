<?php

namespace App\Http\Controllers;

use App\Models\Shape;
use App\Models\Feather;
use App\Models\Color;

class TypeController extends Controller
{
    public function index()
    {
        return view('masters.types-index', [
            'shapes'   => Shape::orderBy('kode')->get(),
            'feathers' => Feather::orderBy('kode')->get(),
            'colors'   => Color::orderBy('kode')->get(),
        ]);
    }
}