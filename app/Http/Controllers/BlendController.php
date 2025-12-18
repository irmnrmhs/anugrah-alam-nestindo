<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Color;
use App\Models\Shape;
use App\Models\Feather;
use App\Models\WBHouse;

class BlendController extends Controller
{
    public function type()
    {
        return view('masters.types', [
            'shapes'   => Shape::latest()->get(),
            'feathers' => Feather::latest()->get(),
            'colors'   => Color::latest()->get(),
        ]);
    }

    public function area_wbhouse() {
        return view('masters.area-wbhouse', [
            'areas'    => Area::latest()->get(),
            'wbhouses' => WBHouse::with('area')->latest()->get(),
        ]);
    }
}
