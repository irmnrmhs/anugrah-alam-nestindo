<?php

namespace App\Http\Controllers;

use App\Models\Shape;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ShapeController extends Controller
{
    public string $obj = 'Jenis Bentuk';

    public function index(): View
    {
        $shapes = Shape::latest()->get();

        return view('masters.shape', compact('shapes'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:50|unique:shapes,kode',
            'jenis_bentuk' => 'required|string|max:100|unique:shapes,jenis_bentuk',
        ]);

        $shape = Shape::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $shape,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $shape = Shape::findOrFail($id);

        return response()->json($shape);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:50|unique:shapes,kode,' . $id,
            'jenis_bentuk' => 'required|string|max:100|unique:shapes,jenis_bentuk,' . $id,
        ]);

        $shape = Shape::findOrFail($id);
        $shape->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $shape,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $shape = Shape::findOrFail($id);
        $shape->delete();
                                                                                                                
        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }
}
