<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FpGrade;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class FpGradeController extends Controller
{
    public function index(): View
    {
        $grades = FpGrade::latest()->get();
        return view('production.fp-grade', compact('grades'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'kode' => 'required|unique:fp_grades,kode',
            'grade' => 'required|unique:fp_grades,grade',
            'keterangan' => 'nullable',
            'status' => 'required|boolean'
        ]);

        $grade = FpGrade::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Departemen berhasil ditambahkan.',
            'data' => $grade,
        ]);
    }
    
    public function show(int $id): JsonResponse{
        $grade = FpGrade::findOrFail($id);
        return response()->json($grade);
    }
    
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'kode' => 'required|unique:fp_grades,kode,' . $id,
            'grade' => 'required|unique:fp_grades,grade,' . $id,
            'keterangan' => 'nullable',
            'status' => 'required|boolean'
        ]);

        $grade = FpGrade::findOrFail($id);
        $grade->update($validated);

        return response()->json([
                'status' => 'success',
                'message' => 'Departemen berhasil diperbaharui.',
                'data' => $grade,
        ]);
    }
    
    public function destroy(int $id): JsonResponse
    {
        $grade = FpGrade::findOrFail($id);
        $grade->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Departemen berhasil dihapus.',
        ]);
    }
}
