<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EmployeesImport;
use App\Exports\EmployeesTemplateExport;
use App\Models\Position;

class EmployeeController extends Controller
{
    public string $obj = 'Karyawan';
    public function index(): View
    {
        $employees = Employee::with('position', 'department')->latest()->get();
        $positions = Position::all();
        $departments = Department::all();

        return view('masters.employee', compact('employees', 'positions', 'departments'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nip' => 'required|string|max:25|unique:employees,nip',
            'nama' => 'required|string|max:255',
            'positions_id' => 'nullable|exists:positions,id',
            'dept_id' => 'required|exists:departments,id',
            'status' => 'required|boolean'
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
            'positions_id' => 'nullable|exists:positions,id',
            'dept_id' => 'required|exists:departments,id',
            'status' => 'required|boolean'
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

    public function import(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        $import = new EmployeesImport();
        Excel::import($import, $request->file('file'));

        return response()->json([
            'status'  => 'success',
            'message' => 'Import selesai',
            'errors'  => $import->getErrors(),
        ]);
    }

    public function downloadTemplate()
    {
        return Excel::download(
            new EmployeesTemplateExport,
            'template_import_karyawan.xlsx'
        );
    }

}