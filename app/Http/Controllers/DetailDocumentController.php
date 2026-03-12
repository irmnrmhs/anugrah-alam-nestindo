<?php

namespace App\Http\Controllers;

use App\Models\DetailDocument;
use App\Models\Employee;
use App\Models\Document;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DetailDocumentController extends Controller
{
    public string $obj = 'Detail Dokumen';

    public function index(): View
    {
        $details = DetailDocument::with('document', 'employee')->latest()->get();
        $employees = Employee::with('position')->where('status', 1)->whereHas('position', function ($query) {
                $query->where('posisi', 'Produksi');
            })->get();
        $documents = Document::all();

        return view('mgmt.detail-document', compact('details', 'employees', 'documents'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'documents_id' => 'required|exists:documents,id',
            'employees_id' => 'required|exists:employees,id',
            'shift' => 'nullable|numeric',
        ]);

        $detail = DetailDocument::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan.',
            'data' => $detail,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $detail = DetailDocument::with('document', 'employee')->findOrFail($id);
        return response()->json($detail);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'documents_id' => 'required|exists:documents,id',
            'employees_id' => 'required|exists:employees,id',
            'shift' => 'nullable|numeric',
        ]);

        $detail = DetailDocument::findOrFail($id);
        $detail->update($validated);

        return response()->json([
                'status'  => 'success',
                'message' => $this->obj . ' berhasil diperbaharui.',
                'data'    => $detail,
        ]);
    }

   public function destroy(int $id): JsonResponse
    {
        $detail = DetailDocument::findOrFail($id);
        $detail->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus.',
        ]);
    }
}
