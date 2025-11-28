<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public string $obj = 'Departemen';
    public function index(): View
    {
        $departments = Department::latest()->get();
        return view('masters.department', compact('departments'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'kd_dept' => 'required|string|max:25|unique:departments,kd_dept',
            'nama_dept' => 'required|string|max:255|unique:departments,nama_dept',
            'deskripsi' => 'nullable|string|max:500',
        ]);

        $department = Department::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $department,
        ]);
    }
    
    public function show(int $id): JsonResponse{
        $department = Department::findOrFail($id);
        return response()->json($department);
    }
    
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'kd_dept' => 'required|string|max:25|unique:departments,kd_dept,' . $id,
            'nama_dept' => 'required|string|max:255|unique:departments,nama_dept,' . $id,
            'deskripsi' => 'nullable|string|max:500',
        ]);

        $department = Department::findOrFail($id);
        $department->update($validated);

        return response()->json([
                'status' => 'success',
                'message' => $this->obj . ' berhasil diperbaharui',
                'data' => $department,
        ]);
    }
    
    public function destroy(int $id): JsonResponse
    {
        $department = Department::findOrFail($id);
        $department->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }
}
