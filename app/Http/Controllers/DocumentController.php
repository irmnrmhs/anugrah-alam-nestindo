<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Employee;
use Illuminate\View\View;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DocumentController extends Controller
{
    public string $obj = 'Dokumen';

    public function index(): View
    {
        $docs = Document::with('department')->latest()->get();
        $employees = Employee::with('position')->where('status', 1)->whereHas('position', function ($query) {
                $query->where('posisi', 'karyawan');
            })->get();
        
        $depts = Department::all();
        return view('mgmt.document', compact('docs', 'employees', 'depts'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'employees_id' => 'required|exists:employees,id',
            'depts_id' => 'required|exists:departments,id',
            'kode' => 'required|unique:documents,kode',
            'no'      => 'required|unique:documents,no',
            'name'      => 'required|unique:documents,name',
            'rev'    => 'integer',
        ]);

        $doc = Document::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan.',
            'data' => $doc,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $doc = Document::with('department')->findOrFail($id);
        return response()->json($doc);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'employees_id' => 'required|exists:employees,id',
            'depts_id' => 'required|exists:departments,id',
            'kode' => 'required|unique:documents,kode,' . $id,
            'no'      => 'required|unique:documents,no,' . $id,
            'name'      => 'required|unique:documents,name,' . $id,
            'rev'    => 'integer'
        ]);

        $doc = Document::findOrFail($id);
        $doc->update($validated);

        return response()->json([
                'status'  => 'success',
                'message' => $this->obj . ' berhasil diperbaharui.',
                'data'    => $doc,
        ]);
    }

   public function destroy(int $id): JsonResponse
    {
        $doc = Document::findOrFail($id);
        $doc->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus.',
        ]);
    }
}
