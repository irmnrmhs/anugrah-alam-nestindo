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
    public string $obj = 'Grade';

    public function index(): View
    {
        $grades = Grade::with('shape', 'feather', 'color')->oldest()->get();
        $shapes = Shape::all();
        $feathers = Feather::all();
        $colors = Color::all();

        return view('raw-material.grade', compact('grades', 'shapes', 'feathers', 'colors'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'shapes_id' => ['required', 'exists:shapes,id'],
            'feathers_id' => ['required', 'exists:feathers,id'],
            'colors_id' => [
                'required',
                'exists:colors,id',
                Rule::unique('grades')->where(fn ($q) =>
                    $q->where('shapes_id', $request->shapes_id)
                    ->where('feathers_id', $request->feathers_id)
                    ->where('colors_id', $request->colors_id)
                ),
            ],
            'status' => 'required|boolean',
        ]);

        $grade = Grade::create($validated);

        $message = "Grade baru berhasil ditambahkan dengan kode {$grade->grade}.";

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
            'shapes_id' => ['required', 'exists:shapes,id'],
            'feathers_id' => ['required', 'exists:feathers,id'],
            'colors_id' => [
                'required',
                'exists:colors,id',
                Rule::unique('grades')->ignore($id)->where(fn ($q) =>
                    $q->where('shapes_id', $request->shapes_id)
                    ->where('feathers_id', $request->feathers_id)
                    ->where('colors_id', $request->colors_id)
                ),
            ],
            'status' => 'required|boolean',
        ]);

        $grade = Grade::findOrFail($id);
        $grade->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Grade berhasil diperbarui.',
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
