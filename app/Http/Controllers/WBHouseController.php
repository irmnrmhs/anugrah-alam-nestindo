<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WBHouse;
use App\Models\Area;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class WBHouseController extends Controller
{
    public function index(): View
    {
        $wbhouses = WBHouse::latest()->get();
        $areas = Area::all();
        return view('masters.wbhouse', compact('wbhouses', 'areas'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'kode'      => 'required|string|max:25|unique:w_b_houses,kode',
            'nama'      => 'required|unique:w_b_houses,nama',
            'alamat'    => 'nullable',
            'areas_id'  => 'required|exists:areas,id',
            'kapasitas' => 'nullable|numeric|min:0|max:99999.99',
        ]);

        $wbhouse = WBHouse::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data Rumah Burung berhasil ditambahkan.',
            'data' => $wbhouse,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $wbhouse = WBHouse::with('area')->findOrFail($id);
        return response()->json($wbhouse);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'kode'      => 'required|string|max:25|unique:w_b_houses,kode,' . $id,
            'nama'      => 'required|unique:w_b_houses,kode,' . $id,
            'alamat'    => 'nullable',
            'areas_id'  => 'required|exists:areas,id',
            'kapasitas' => 'nullable|numeric|min:0|max:99999.99',

        ]);

        $wbhouse = WBHouse::findOrFail($id);
        $wbhouse->update($validated);

        return response()->json([
                'status'  => 'success',
                'message' => 'Data Rumah Burung berhasil diperbaharui.',
                'data'    => $wbhouse,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $wbhouse = WBHouse::findOrFail($id);
        $wbhouse->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data Rumah Burung berhasil dihapus.',
        ]);
    }
}