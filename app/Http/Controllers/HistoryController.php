<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\ProductIdentifier;
use Illuminate\View\View;

class HistoryController extends Controller
{
    public string $obj = 'Riwayat';

    public function index(): View
    {
        $histories = History::with('gcolor')->latest()->get();
        $identifiers = ProductIdentifier::all();

        return view('production.history', compact('histories'));
    }
}
