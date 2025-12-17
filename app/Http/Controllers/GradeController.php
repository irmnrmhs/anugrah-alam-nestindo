<?php

namespace App\Http\Controllers;

use App\Models\Shape;
use App\Models\Feather;
use App\Models\Color;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class GradeController extends Controller
{
    public string $obj = 'Grade Bahan Baku';

    public function index(): View
    {
        $grades = Grade::with('shape', 'feather', 'color')->latest()->get();
        $shapes = Shape::all();
        $feathers = Feather::all();
        $colors = Color::all();

        return view('raw-material.grade', compact('grades', 'shapes', 'feathers', 'colors'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'shapes_id' => 'required|exists:shapes,id',
            'feathers_id' => 'required|exists:feathers,id',
            'colors_id' => 'required|exists:colors,id',
            'status' => 'required|boolean',
        ]);

        $shapes = Shape::find($validated['shapes_id']);
        $feather = Feather::find($validated['feathers_id']);
        $color = Color::find($validated['colors_id']);
        $validated['grade'] = strtoupper($shapes->kode . "-" . $feather->kode . "-" . $color->kode);

        $grade = Grade::create($validated);

        $message = $this->obj . " berhasil ditambahkan dengan kode {$grade->grade}.";

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $grade,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $grade = Grade::with('shape', 'feather', 'color')->findOrFail($id);
        return response()->json($grade);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'shapes_id' => 'required|exists:shapes,id',
            'feathers_id' => 'required|exists:feathers,id',
            'colors_id' => 'required|exists:colors,id',
            'status' => 'required|boolean',
        ]);

        $shapes = Shape::find($validated['shapes_id']);
        $feather = Feather::find($validated['feathers_id']);
        $color = Color::find($validated['colors_id']);
        $validated['grade'] = strtoupper($shapes->kode . "-" . $feather->kode . "-" . $color->kode);
        
        $grade = Grade::findOrFail($id);
        $grade->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui.',
            'data' => $grade,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $grade = Grade::findOrFail($id);
        $grade->delete();

        return response()->json([
            'status' => 'success',
            'message' => "{$this->obj} berhasil dihapus",
        ]);
    }
}
