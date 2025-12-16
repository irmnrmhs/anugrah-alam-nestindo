<?php

namespace App\Http\Controllers;

use App\Models\FinishedProduct;
use App\Models\NestType;
use App\Models\Steam;
use App\Models\SteamOfficer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class SteamController extends Controller
{
    public string $obj = 'Steam';
    public function index(): View
    {
        $steams = Steam::with('officer', 'nest', 'fproduct')->latest()->get();
        $officers = SteamOfficer::all();
        $nests = NestType::all();
        $fproducts = FinishedProduct::all();

        return view('production.steam', compact('steams', 'officers', 'nests', 'fproducts'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'fproducts_id' => 'required|exists:finished_products,id',
            'officers_id' => 'required|exists:steam_officers,id',
            'nests_id' => 'required|exists:nest_types,id',
            'penambahan' => 'boolean',
            'sumber_panas' => 'boolean',
            'tgl_pemanasan' => 'required|date',
            'standar' => 'boolean',
            'suhu_awal' => 'required|numeric|max:999.99',
            'biji' => 'required|integer|min:0',
            'berat' => 'required|numeric|min:0|max:99999.99',
            'suhu' => 'required|numeric|max:99999.99',
            'waktu' => 'required|date_format:H:i',
            'suhu_total' => 'required|numeric|max:999.99',
            'waktu_total' => 'required|date_format:H:i',
            'jml_tray' => 'required|integer|min:0|max:6',
            'keterangan' => 'nullable'
        ]);

        $steam = Steam::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $steam,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $steam = Steam::with('officer', 'nest', 'fproduct')->findOrFail($id);
        return response()->json($steam);
    }


    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'fproducts_id' => 'required|exists:finished_products,id',
            'officers_id' => 'required|exists:steam_officers,id',
            'nests_id' => 'required|exists:nest_types,id',
            'penambahan' => 'boolean',
            'sumber_panas' => 'boolean',
            'tgl_pemanasan' => 'required|date',
            'standar' => 'boolean',
            'suhu_awal' => 'required|numeric|max:999.99',
            'biji' => 'required|integer|min:0',
            'berat' => 'required|numeric|min:0|max:99999.99',
            'suhu' => 'required|numeric|max:99999.99',
            'waktu' => 'required|date_format:H:i',
            'suhu_total' => 'required|numeric|max:999.99',
            'jml_tray' => 'required|integer|min:0|max:6',
            'waktu_total' => 'required|date_format:H:i',
            'keterangan' => 'nullable'
        ]);

        $steam = Steam::findOrFail($id);

        $steam->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $steam,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $steam = Steam::findOrFail($id);
        $steam->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }

    public function bulk(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.fproducts_id'   => 'required|exists:finished_products,id',
            'items.*.officers_id'   => 'required|exists:steam_officers,id',
            'items.*.nests_id' => 'required|exists:nest_types,id',
            'items.*.penambahan' => 'boolean',
            'items.*.sumber_panas' => 'boolean',
            'items.*.tgl_pemanasan' => 'required|date',
            'items.*.standar' => 'boolean',
            'items.*.suhu_awal' => 'required|numeric|max:999.99',
            'items.*.biji' => 'required|integer|min:0',
            'items.*.berat' => 'required|numeric|min:0|max:99999.99',
            'items.*.suhu' => 'required|numeric|max:99999.99',
            'items.*.waktu' => 'required|date_format:H:i',
            'items.*.suhu_total' => 'required|numeric|max:999.99',
            'items.*.jml_tray' => 'required|integer|min:0|max:6',
            'items.*.waktu_total' => 'required|date_format:H:i',
            'items.*.keterangan' => 'nullable'
        ]);

        $items = $validated['items'];

        foreach ($items as $item) {
            Steam::create($item);
        }

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan.',
        ]);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        Steam::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}
