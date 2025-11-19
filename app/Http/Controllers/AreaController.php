<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class AreaController extends Controller
{
    public string $obj = 'Area';

    public function index(): View
    {
        $areas = Area::oldest()->get();
        return view('masters.area', compact('areas'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'kode' => 'required|unique:areas,kode',
            'area' => 'required|unique:areas,area',
            'kh' => 'required|boolean',
            'keterangan' => 'nullable'
        ]);

        $area = Area::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $area,
        ]);
    }
    
    public function show(int $id): JsonResponse{
        $area = Area::findOrFail($id);
        return response()->json($area);
    }
    
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'kode' => 'required|unique:areas,kode,' . $id,
            'area' => 'required|unique:areas,area,' . $id,
            'kh' => 'required|boolean',
            'keterangan' => 'nullable'
        ]);

        $area = Area::findOrFail($id);
        $area->update($validated);

        return response()->json([
                'status' => 'success',
                'message' => $this->obj . ' berhasil diperbaharui',
                'data' => $area,
        ]);
    }
    
    public function destroy(int $id): JsonResponse
    {
        $department = Area::findOrFail($id);
        $department->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }
}
