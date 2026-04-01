<?php

namespace App\Http\Controllers;

use App\Models\FpGrade;
use App\Models\Item;
use App\Models\ItemDetail;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ItemDetailController extends Controller
{
    public string $obj = 'Karyawan';
    public function index(): View
    {
        $ditems = ItemDetail::with('grade', 'item')->latest()->get();
        $grades = FpGrade::all();
        $items = Item::all();

        return view('masters.item-detail', compact('ditems', 'grades', 'items'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'grades_id' => 'required|exists:fp_grades,id',
            'items_id' => 'required|exists:items,id',
            'specification' => 'required',
            'price' => 'integer',
            'ket' => 'nullable',
        ]);

        ItemDetail::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $ditem = ItemDetail::with('grade', 'item')->findOrFail($id);
        return response()->json($ditem);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'grades_id' => 'required|exists:fp_grades,id',
            'items_id' => 'required|exists:items,id',
            'specification' => 'required',
            'price' => 'integer',
            'ket' => 'nullable',
        ]);

        $ditem = ItemDetail::findOrFail($id);

        $ditem->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $ditem,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $ditem = ItemDetail::findOrFail($id);
        $ditem->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        ItemDetail::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}
