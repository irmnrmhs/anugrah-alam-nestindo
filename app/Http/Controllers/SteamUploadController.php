<?php

namespace App\Http\Controllers;

use App\Models\FinishedProduct;
use App\Models\SteamUpload;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class SteamUploadController extends Controller
{
    public string $obj = 'Dokumen Steaming';
    public function index(): View
    {
        $uploads = SteamUpload::with('fproduct')->latest()->get();
        $fproducts = FinishedProduct::all();

        return view('production.steam-upload', compact('uploads','fproducts'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'fproducts_id' => 'required|exists:finished_product,id',
            'file' => 'required',
        ]);

        SteamUpload::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $upload = SteamUpload::with('fproduct')->findOrFail($id);
        return response()->json($upload);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'fproducts_id' => 'required|exists:finished_product,id',
            'file' => 'required',
        ]);

        $upload = SteamUpload::findOrFail($id);

        $upload->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $upload,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $upload = SteamUpload::findOrFail($id);
        $upload->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        SteamUpload::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}
