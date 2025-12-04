<?php

namespace App\Http\Controllers;

use App\Models\Edge;
use App\Models\History;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class EdgeController extends Controller
{
    public string $obj = 'Kedatangan';
    public function index(): View
    {
        $edges = Edge::with('history', 'employee')->latest()->get();
        $histories = History::all();
        $employees = Employee::all();

        return view('production.edge', compact('edges', 'histories', 'employees'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'histories_id' => 'required|exists:histories,id',
            'employees_id' => 'required|exists:employees,id',
            'tgl_mulai' => 'required|date',
            'biji_masuk' => 'required|integer|min:0',
            'berat_masuk' => 'required|numeric|min:0|max:99999.99',
            'employees_id' => 'required|exists:employees,id',
            'tgl_selesai' => 'required|date',
            'biji_keluar' => 'required|integer|min:0',
            'berat_keluar' => 'required|numeric|min:0|max:99999.99'
        ]);

        $edge = Edge::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $edge,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $edge = Edge::findOrFail($id);
        return response()->json($edge);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'histories_id' => 'required|exists:histories,id',
            'employees_id' => 'required|exists:employees,id',
            'tgl_mulai' => 'required|date',
            'biji_masuk' => 'required|integer|min:0',
            'berat_masuk' => 'required|numeric|min:0|max:99999.99',
            'employees_id' => 'required|exists:employees,id',
            'tgl_selesai' => 'required|date',
            'biji_keluar' => 'required|integer|min:0',
            'berat_keluar' => 'required|numeric|min:0|max:99999.99'
        ]);

        $edge = Edge::findOrFail($id);
        
        $edge->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $edge,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $edge = Edge::findOrFail($id);
        $edge->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data kedatangan berhasil dihapus.',
        ]);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        Edge::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}
