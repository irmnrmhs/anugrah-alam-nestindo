<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class PositionController extends Controller
{
    public string $obj = 'Jabatan';
    public function index(): View
    {
        $positions = Position::latest()->get();
        return view('masters.position', compact('positions'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'posisi' => 'required|string|unique:positions,posisi',
            'keterangan' => 'nullable',
        ]);

        $position = Position::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan.',
            'data' => $position,
        ]);
    }
    
    public function show(int $id): JsonResponse{
        $position = Position::findOrFail($id);
        return response()->json($position);
    }
    
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'posisi' => 'required|string|unique:positions,posisi,' . $id,
            'keterangan' => 'nullable',
        ]);

        $position = Position::findOrFail($id);
        $position->update($validated);

        return response()->json([
                'status' => 'success',
                'message' => $this->obj . ' berhasil diperbaharui.',
                'data' => $position,
        ]);
    }
    
    public function destroy(int $id): JsonResponse
    {
        $position = Position::findOrFail($id);
        $position->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus.',
        ]);
    }
}
