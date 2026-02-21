<?php

namespace App\Http\Controllers;

use App\Models\Shape;
use App\Models\Feather;
use App\Models\Color;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class GradeController extends Controller
{
    public string $obj = 'Grade Bahan Baku';

    public function index(): View
    {
        $grades = Grade::with('feather', 'color')->latest()->get();
        $feathers = Feather::all();
        $colors = Color::all();

        return view('raw-material.grade', compact('grades', 'feathers', 'colors'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'feathers_id' => 'required|exists:feathers,id',
            'colors_id' => 'required|exists:colors,id',
            'status' => 'required|boolean',
        ]);

        $feather = Feather::find($validated['feathers_id']);
        $color = Color::find($validated['colors_id']);
        $validated['grade'] = strtoupper($feather->kode . "-" . $color->kode);

        $grade = Grade::create($validated);

        $message = $this->obj . " berhasil ditambahkan dengan kode {$grade->grade}.";

        return response()->json([
            'status' => 'success',
            'message' => $message,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $grade = Grade::with('feather', 'color')->findOrFail($id);
        return response()->json($grade);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'feathers_id' => 'required|exists:feathers,id',
            'colors_id' => 'required|exists:colors,id',
            'status' => 'required|boolean',
        ]);

        $feather = Feather::find($validated['feathers_id']);
        $color = Color::find($validated['colors_id']);
        $validated['grade'] = strtoupper($feather->kode . "-" . $color->kode);
        
        $grade = Grade::findOrFail($id);
        $grade->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui.',
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
