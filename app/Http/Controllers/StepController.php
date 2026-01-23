<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Step;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class StepController extends Controller
{
    public string $obj = 'Daftar Proses';
    public function index(): View
    {
        $steps = Step::latest()->get();
        $departments = Department::all();

        return view('masters.step', compact('steps', 'departments'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'depts_id' => 'required|exists:departments,id',
            'kode' => 'required|unique:steps,kode',
            'proses' => 'required|unique:steps,proses',
            'ket' => 'nullable',
        ]);

        $step = Step::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $step,
        ]);
    }
    
    public function show(int $id): JsonResponse{
        $step = Step::findOrFail($id);
        return response()->json($step);
    }
    
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'depts_id' => 'required|exists:departments,id',
            'kode' => 'required|unique:steps,kode,' . $id,
            'proses' => 'required|unique:steps,proses,' . $id,
            'ket' => 'nullable',
        ]);

        $step = Step::findOrFail($id);
        $step->update($validated);

        return response()->json([
                'status' => 'success',
                'message' => $this->obj . ' berhasil diperbaharui.',
                'data' => $step,
        ]);
    }
    
    public function destroy(int $id): JsonResponse
    {
        $step = Step::findOrFail($id);
        $step->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus.',
        ]);
    }
}
