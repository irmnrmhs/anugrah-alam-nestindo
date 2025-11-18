<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Shape;
use App\Models\Feather;
use App\Models\Color;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class GradeController extends Controller
{
    public string $obj = 'Grade';
    public function index(): View
    {
        $grades = Grade::with('category', 'shape', 'feather', 'color')->oldest()->get();
        $categories = Category::all();
        $shapes = Shape::all();
        $feathers = Feather::all();
        $colors = Color::all();

        return view('raw-material.grade', compact('grades', 'categories', 'shapes', 'feathers', 'colors'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'categories_id' => 'required|exists:categories,id',
            // 'grade' => 'required|string|max:100|unique:grades,grade',
            'shapes_id' => 'required|exists:shapes,id',
            'feathers_id' => 'required|exists:feathers,id',
            'colors_id' => 'required|exists:colors,id',
            'status' => 'required|boolean',
        ]);

        // Ambil kode relasi
        $shape = Shape::find($request->shapes_id);
        $feather = Feather::find($request->feathers_id);
        $color = Color::find($request->colors_id);

        // Generate grade otomatis (tanpa kategori)
        $generatedGrade = strtoupper(
            $shape->kode . '-' .
            $feather->kode . '-' .
            $color->kode
        );

        // Tambahkan ke validated
        $validated['grade'] = $generatedGrade;

        $grade = Grade::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Grade berhasil ditambahkan',
            'data' => $grade,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $grade = Grade::with('category', 'shape', 'feather', 'color')->findOrFail($id);
        return response()->json($grade);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'categories_id' => 'required|exists:categories,id',
            // 'grade' => 'required|string|max:100|unique:grades,grade,' . $id,
            'shapes_id' => 'required|exists:shapes,id',
            'feathers_id' => 'required|exists:feathers,id',
            'colors_id' => 'required|exists:colors,id',
            'status' => 'required|boolean',
        ]);

        $shape = Shape::find($request->shapes_id);
        $feather = Feather::find($request->feathers_id);
        $color = Color::find($request->colors_id);

        // Generate grade baru
        $validated['grade'] = strtoupper(
            $shape->kode . '-' .
            $feather->kode . '-' .
            $color->kode
        );

        $grade = Grade::findOrFail($id);
        $grade->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Grade berhasil diperbarui',
            'data' => $grade,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $grade = Grade::findOrFail($id);
        $grade->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }
}
