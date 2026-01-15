<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Document;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DocumentController extends Controller
{
    public string $obj = 'Dokumen';

    public function index(): View
    {
        $docs = Document::with('department')->latest()->get();
        $departments = Department::all();
        return view('mgmt.document', compact('docs', 'departments'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'depts_id' => 'required|exists:departments,id',
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
            'depts_id' => 'required|exists:departments,id',
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
