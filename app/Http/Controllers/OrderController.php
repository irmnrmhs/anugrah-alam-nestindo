<?php

namespace App\Http\Controllers;

use App\Models\FinishedProduct;
use App\Models\Order;
use App\Models\Export;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class OrderController extends Controller
{
    public string $obj = 'Detail Penerbangan';
    public function index(): View
    {
        $orders = Order::with('export', 'batch', 'item')->latest()->get();
        $exports = Export::all();
        $batchs = FinishedProduct::all();
        $items = Item::all();

        return view('orders.order', compact('orders', 'exports', 'batchs', 'items'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'exports_id' => 'required|exists:exports,id',
            'batch_id' => 'required|exists:finished_products,id',
            'items_id' => 'required|exists:items,id',
            'packaging' => 'required|numeric',
            'label' => 'required|numeric',
            'net' => 'required|numeric',
            'gross' => 'required|numeric',
            'amount_fob' => 'required|numeric',
            'amount_cif' => 'required|numeric',
        ]);

        Order::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $order = Order::with('export', 'batch', 'item')->findOrFail($id);
        return response()->json($order);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'exports_id' => 'required|exists:exports,id',
            'batch_id' => 'required|exists:finished_products,id',
            'items_id' => 'required|exists:items,id',
            'packaging' => 'required|numeric',
            'label' => 'required|numeric',
            'net' => 'required|numeric',
            'gross' => 'required|numeric',
            'amount_fob' => 'required|numeric',
            'amount_cif' => 'required|numeric',
        ]);

        $order = Order::findOrFail($id);

        $order->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $order,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        Order::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}
