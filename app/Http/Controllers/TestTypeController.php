<?php

namespace App\Http\Controllers;

use App\Models\TestType;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class TestTypeController extends Controller
{
    public string $obj = 'Jenis Uji';
    public function index(): View
    {
        $testTypes = TestType::with('category')->oldest()->get();
        $categories = Category::all();

        return view('masters.testType', compact('testTypes', 'categories'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'categories_id' => 'required|exists:categories,id',
            'nama_uji' => 'required|string|max:100',
            'satuan' => 'required|string|max:100',
            'standar_maksimal' => 'required|numeric|min:0|max:999.999',
            'standar_minimal' => 'required|numeric|min:0|max:999.999',
        ]);

        $testType = TestType::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $testType,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $testType = TestType::with('category')->findOrFail($id);
        return response()->json($testType);
    }


    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'categories_id' => 'required|exists:categories,id',
            'nama_uji' => 'required|string|max:100',
            'satuan' => 'required|string|max:100',
            'standar_maksimal' => 'required|numeric|min:0|max:999.999',
            'standar_minimal' => 'required|numeric|min:0|max:999.999',
        ]);

        $testType = TestType::findOrFail($id);

        $testType->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $testType,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $testType = TestType::findOrFail($id);
        $testType->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }
}