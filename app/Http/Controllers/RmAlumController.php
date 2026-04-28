<?php

namespace App\Http\Controllers;

use App\Models\RmAlum;
use App\Models\Document;
use Illuminate\View\View;
use App\Models\RawMaterial;
use App\Models\TestType;
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

        $alum = TestType::where('kode', 'QCBBL')->first();

        $isValid = 
            $validated['kadar_aluminium'] > $alum->standar_minimal && $validated['kadar_aluminium'] < $alum->standar_maksimal;

        $validated['hasil'] = $isValid ? 1 : 0;
        
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
            'rms_id' => 'required|exists:raw_materials,id',
            'tgl' => 'required|date',
            'kadar_aluminium' => 'required|numeric|min:0|max:999.9'
        ]);
        
        $result = RmAlum::findOrFail($id);
        $alum = TestType::where('kode', 'QCBBL')->first();

        $isValid = 
            $validated['kadar_aluminium'] > $alum->standar_minimal && $validated['kadar_aluminium'] < $alum->standar_maksimal;

        $validated['hasil'] = $isValid ? 1 : 0;
        
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
            'items.*.kadar_aluminium' => 'required|numeric|min:0|max:999.9'
        ]);

        $items = $validated['items'];

        $alum = TestType::where('kode', 'QCBBL')->first();

        foreach ($items as $item) {
            $isValid = 
                $item['kadar_aluminium'] > $alum->standar_minimal && $item['kadar_aluminium'] < $alum->standar_maksimal;

            $item['hasil'] = $isValid ? 1 : 0;

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
