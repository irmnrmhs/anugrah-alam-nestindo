<?php

namespace App\Http\Controllers;

use App\Models\CcpAlum;
use Illuminate\View\View;
use App\Models\RawMaterial;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CcpAlumController extends Controller
{
    public string $obj = 'Hasil Uji Aluminium Selama Proses (CCP1)';
    public function index(): View
    {
        $results = CcpAlum::with('rawMaterial')->latest()->get();
        $rms = RawMaterial::all();

        return view('quality-control.ccp-alum', compact('results', 'rms'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'rms_id' => 'required|exists:raw_materials,id',
            'ccp_al' => 'required|numeric|min:0|max:999.9',
        ]);

        if($validated['ccp_al'] > 100){
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak memenuhi standar Kadar Aluminium'
            ]);
        }

        $result = CcpAlum::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $result,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $result = CcpAlum::findOrFail($id);
        return response()->json($result);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'products_id' => 'required|exists:raw_materials,id',
            'ccp_al' => 'required|numeric|min:0|max:999.9'
        ]);

        if($validated['ccp_al'] > 100)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak memenuhi standar Kadar Aluminium'
            ]);
        }
        
        $result = CcpAlum::findOrFail($id);
        
        $result->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $result,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $result = CcpAlum::findOrFail($id);
        $result->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }

    public function bulk(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.products_id'   => 'required|exists:raw_materials,id',
            'items.*.ccp_al' => 'nullable|numeric|min:0|max:999.9'
        ]);

        $items = $validated['items'];

        foreach ($items as $item) {
            CcpAlum::create($item);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Semua hasil uji berhasil ditambahkan.',
        ]);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        CcpAlum::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}
