<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ItemController extends Controller
{
    public string $obj = 'Item';
    public function index(): View
    {
        $items = Item::latest()->get();

        return view('masters.item', compact('items'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'item' => 'required|unique:items,item',
            'item_cn' => 'nullable',
        ]);

        Item::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $item = Item::findOrFail($id);
        return response()->json($item);
    }


    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'item' => 'required|unique:items,item,' . $id,
            'item_cn' => 'nullable',
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
