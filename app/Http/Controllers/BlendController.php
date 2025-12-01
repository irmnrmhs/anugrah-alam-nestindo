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
            'shapes'   => Shape::orderBy('kode')->get(),
            'feathers' => Feather::orderBy('kode')->get(),
            'colors'   => Color::orderBy('kode')->get(),
        ]);
    }

    public function area_wbhouse() {
        return view('masters.area-wbhouse', [
            'areas'    => Area::orderBy('kode')->get(),
            'wbhouses' => WBHouse::with('area')->orderBy('kode')->get(),
        ]);
    }
}
