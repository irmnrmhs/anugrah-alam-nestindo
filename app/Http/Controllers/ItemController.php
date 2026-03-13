<?php

namespace App\Http\Controllers;

use App\Models\FpGrade;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ItemController extends Controller
{
    public string $obj = 'Karyawan';
    public function index(): View
    {
        $items = Item::with('grade')->latest()->get();
        $grades = FpGrade::all();

        return view('masters.item', compact('items', 'grades'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'grades_id' => 'required|exists:fp_grades,id',
            'item' => 'required',
            'item_cn' => 'nullable',
            'specification' => 'required',
            'price' => 'integer',
            'ket' => 'nullable',
        ]);

        $item = Item::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $item = Item::with('grade')->findOrFail($id);
        return response()->json($item);
    }


    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'grades_id' => 'required|exists:fp_grades,id',
            'item' => 'required',
            'item_cn' => 'nullable',
            'specification' => 'required',
            'price' => 'integer',
            'ket' => 'nullable',
        ]);

        $item = Item::findOrFail($id);

        $item->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $item,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        Item::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}
