<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::oldest()->get();

        return view('masters.category', compact('categories'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:25|unique:categories,kode',
            'kategori' => 'required|string|max:50|unique:categories,kategori',
            'keterangan' => 'nullable',
        ]);

        $category = Category::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Kategori berhasil ditambahkan.',
            'data' => $category,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $category = Category::findOrFail($id);

        return response()->json($category);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:25|unique:categories,kode,' . $id,
            'kategori' => 'required|string|max:50|unique:categories,kategori,' . $id,
            'keterangan' => 'nullable',
        ]);

        $category = Category::findOrFail($id);
        $category->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Kategori berhasil diperbarui.',
            'data' => $category,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $category = Category::findOrFail($id);
        $category->delete();
                                                                                                                
        return response()->json([
            'status' => 'success',
            'message' => 'Kategori berhasil dihapus.',
        ]);
    }
}
