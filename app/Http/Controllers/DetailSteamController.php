<?php

namespace App\Http\Controllers;

use App\Models\DetailSteam;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class DetailSteamController extends Controller
{
    public string $obj = 'Detail Steam';
    public function index(): View
    {
        $dsteams = DetailSteam::with('order')->latest()->get();
        $orders = Order::all();

        return view('production.detail-steam', compact('dsteams', 'orders'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'orders_id' => 'required|exists:orders,id',
            'suhu_preheating' => 'required|numeric|max:99999.99',
            'waktu_preheating' => 'required|date_format:H:i',
            'suhu_total' => 'required|numeric|max:999.99',
            'waktu_total' => 'required|date_format:H:i',
            'jml_tray' => 'required|integer|min:0|max:6',
        ]);
        
        DetailSteam::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $dsteam = DetailSteam::with('order')->findOrFail($id);
        return response()->json($dsteam);
    }


    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'orders_id' => 'required|exists:orders,id',
            'suhu_preheating' => 'required|numeric|max:99999.99',
            'waktu_preheating' => 'required|date_format:H:i',
            'suhu_total' => 'required|numeric|max:999.99',
            'waktu_total' => 'required|date_format:H:i',
            'jml_tray' => 'required|integer|min:0|max:6',
        ]);

        $dsteam = DetailSteam::with('order')->findOrFail($id);

        $dsteam->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbaharui',
            'data' => $dsteam,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $dsteam = DetailSteam::findOrFail($id);
        $dsteam->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        DetailSteam::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}
