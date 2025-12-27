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
            'shapes'   => Shape::latest()->paginate(10),
            'feathers' => Feather::latest()->paginate(10),
            'colors'   => Color::latest()->paginate(10),
        ]);
    }

    public function area_wbhouse() {
        return view('masters.area-wbhouse', [
            'areas'    => Area::latest()->get(),
            'wbhouses' => WBHouse::with('area')->latest()->get(),
        ]);
    }
}
