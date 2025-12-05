<?php

namespace App\Http\Controllers;

use App\Models\Dcertificate;
use App\Models\DetailSkp;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class DetailSkpController extends Controller
{
    public string $obj = 'Detail Pengiriman';
    public function index(): View
    {
        $details = DetailSkp::with('dcertificate')->latest()->get();
        $dcertificates = Dcertificate::all();

        return view('raw-material.detail-skp', compact('details', 'dcertificates'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'dcertificates_id' => 'required|exists:dcertificates,id',
            'tgl_panen' => 'required|date',
            'berat_panen' => 'required|numeric|min:0|max:99999.99',
            'tgl_kirim' => 'required|date',
            'berat_kirim' => 'required|numeric|min:0|max:99999.99'
        ]);

        $detail = DetailSkp::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $detail,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $detail = DetailSkp::with('dcertificate')->findOrFail($id);
        return response()->json($detail);
    }


    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'dcertificates_id' => 'required|exists:dcertificates,id',
            'tgl_panen' => 'required|date',
            'berat_panen' => 'required|numeric|min:0|max:99999.99',
            'tgl_kirim' => 'required|date',
            'berat_kirim' => 'required|numeric|min:0|max:99999.99'
        ]);

        $detail = DetailSkp::findOrFail($id);

        $detail->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $detail,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $detail = DetailSkp::findOrFail($id);
        $detail->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        DetailSkp::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}
