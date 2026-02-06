<?php

namespace App\Http\Controllers;

use App\Models\RmAlum;
use App\Models\Document;
use Illuminate\View\View;
use App\Models\RawMaterial;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;

class RmAlumController extends Controller
{
    public string $obj = 'Hasil Uji Aluminium Bahan Baku';
    public function index(): View
    {
        $results = RmAlum::with('rawMaterial')->latest()->get();
        $rms = RawMaterial::all();

        return view('quality-control.rm-alum', compact('results', 'rms'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'rms_id' => 'required|exists:raw_materials,id',
            'tgl' => 'required|date',
            'kadar_aluminium' => 'required|numeric|min:0|max:999.9',
        ]);

        if($validated['kadar_aluminium'] > 100){
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak memenuhi standar Kadar Aluminium'
            ]);
        }

        $result = RmAlum::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $result,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $result = RmAlum::findOrFail($id);
        return response()->json($result);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'products_id' => 'required|exists:raw_materials,id',
            'tgl' => 'required|date',
            'kadar_aluminium' => 'required|numeric|min:0|max:999.9'
        ]);

        if($validated['kadar_aluminium'] > 100)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak memenuhi standar Kadar Aluminium'
            ]);
        }
        
        $result = RmAlum::findOrFail($id);
        
        $result->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $result,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $result = RmAlum::findOrFail($id);
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
            'items.*.rms_id'   => 'required|exists:raw_materials,id',
            'items.*.tgl' => 'required|date',
            'items.*.kadar_aluminium' => 'nullable|numeric|min:0|max:999.9'
        ]);

        $items = $validated['items'];

        foreach ($items as $item) {
            RmAlum::create($item);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Semua hasil uji berhasil ditambahkan.',
        ]);
    }

    public function export($id)
    {
        $bbs = RmAlum::with([
            'rawMaterial'
        ])
        ->findOrFail($id);

        $document = Document::with([
            'employee',
            'department'
        ])
        ->where('kode', 'QCBBL')
        ->firstOrFail();

        $pdf = Pdf::loadView('exports.rml-form', compact('bbs', 'document'))
                ->setPaper('A4', 'portrait');

        $filename = 'Aluminium Bahan Baku.pdf';

        return $pdf->stream($filename);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        RmAlum::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}
