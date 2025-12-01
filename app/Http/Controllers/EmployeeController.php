<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public string $obj = 'Karyawan';
    public function index(): View
    {
        $employees = Employee::with('department')->latest()->get();
        $departments = Department::all();

        return view('masters.employee', compact('employees', 'departments'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nip' => 'required|string|max:25|unique:employees,nip',
            'nama' => 'required|string|max:255',
            'dept_id' => 'required|exists:departments,id',
        ]);

        $employee = Employee::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $employee,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $employee = Employee::findOrFail($id);
        return response()->json($employee);
    }


    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'nip' => 'required|string|max:25|unique:employees,nip,' . $id,
            'nama' => 'required|string|max:255',
            'dept_id' => 'required|exists:departments,id',
        ]);

        $employee = Employee::findOrFail($id);

        $employee->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $employee,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        Employee::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}