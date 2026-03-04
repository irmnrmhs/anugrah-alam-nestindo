<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Packing;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PackingController extends Controller
{
    public string $obj = 'Packing';
    public function index(): View
    {
        $packs = Packing::with('pack')->latest()->get();
        $orders = Order::all();

        return view('production.packing', compact('packs', 'orders'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'orders_id' => 'required|exists:orders,id',
            'tanggal' => 'required|date',
        ]);

        $pack = Packing::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $pack,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $pack = Packing::findOrFail($id);
        return response()->json($pack);
    }


    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'orders_id' => 'required|exists:orders,id',
            'tanggal' => 'required|date',
        ]);

        $pack = Packing::findOrFail($id);

        $pack->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $pack,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $pack = Packing::findOrFail($id);
        $pack->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        Packing::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}
